<?php

require_once 'HTTP/Request2.php';
require_once('Logger.class.php');
require_once('FileHandler.class.php');

class MessageCollector {

  private $fileHandler;
  private $logger;
  private $fileName = 'persistence/iplist.txt';
  private $keepCollecting = true;

  function __construct() {
    $this->fileHandler = new FileHandler();
    $this->logger = new Logger();
  }

  public function collect() {
    $ipList = $this->fileHandler->deserialize($this->fileName);

    if (is_array($ipList)) {
      foreach ($ipList as $server) {
        $this->keepCollecting = true;
        if ($server['IP'] != $_SERVER['SERVER_ADDR']) {
          do {
            $this->getExternalLog($server['IP']);
          } while ($this->keepCollecting == true);
        }
      }
    }
  }

  private function getExternalLog($ip) {
    $request = new HTTP_Request2('http://' . $ip . '/Architektur-Verteilter-Systeme/a2/getLoggerHTML.php', HTTP_Request2::METHOD_GET);

    try {
      $response = $request->send();

      if (200 == $response->getStatus()) {
        $entryJson = $response->getBody();
        $entryArray = json_decode($entryJson, true);

        $entry['time'] = strtotime($entryArray['message']['time']);
        $entry['from'] = $entryArray['message']['from'];
        $entry['message'] = $entryArray['message']['message'];
        $entry['more'] = $entryArray['more'];

        $this->logger->log($entry);

        if ($entry['more'] < 1) {
          $this->keepCollecting = false;
        }
      } else {
        echo 'Unerwarteter HTTP-Status vom Server' . $ip . ':'  . $response->getStatus() . '. ' . $response->getReasonPhrase() . ' ';
      }
    } catch (HTTP_Request2_Exception $e) {
      echo 'Fehler: ' . $e->getMessage();
      var_dump(http_response_code(500));
      $this->keepCollecting = false;
    } finally {
      var_dump(http_response_code($response->getStatus()));
    }
  }
}