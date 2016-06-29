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

        error_log("MessageCollector: Check if " . $server['IP'] . " equals local server " . $ipList["myIP"] . " ...");

        if ($server['IP'] != $ipList["myIP"]) {
          do {
            $this->getExternalLog($server['IP']);
          } while ($this->keepCollecting == true);
        }
      }
    }
  }

  private function getExternalLog($ip) {
    error_log("MessageCollector: getExternalLog() for" . $ip . " ...");
    $request = new HTTP_Request2('http://' . $ip . '/Architektur-Verteilter-Systeme/a2/getLoggerHTML.php', HTTP_Request2::METHOD_GET);

    try {
      $response = $request->send();

      if (200 == $response->getStatus()) {
        $entryJson = $response->getBody();
        $entryArray = json_decode($entryJson, true);

        $entry['time'] = strtotime($entryArray['message']['time']);
        $entry['from'] = $entryArray['message']['from'];
        $entry['message'] = $entryArray['message']['message'];

        if (!empty($entry['time']) && !empty($entry['from']) && !empty($entry['message'])) {
          $this->logger->log($entry);
        }

        if ($entryArray['more'] < 1) {
          $this->keepCollecting = false;
        }
      } else {
        $this->keepCollecting = false;
      }
    } catch (HTTP_Request2_Exception $e) {
      $this->keepCollecting = false;
    }
  }
}