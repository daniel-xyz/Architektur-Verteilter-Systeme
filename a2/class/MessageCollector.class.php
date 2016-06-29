<?php

require_once 'HTTP/Request2.php';
require_once('FileHandler.class.php');

class MessageCollector {

  private $fileHandler;
  private $keepCollecting = true;

  function __construct() {

  }

  public function collect() {
    $this->fileHandler = new FileHandler();
    $ipList = $this->fileHandler->deserialize('persistence/iplist.txt');

    if (is_array($ipList) && array_key_exists('all', $ipList) && count($ipList['all']) > 0) {
      foreach ($ipList['all'] as $server) {
        $this->keepCollecting = true;

        if ($server['IP'] != $ipList['me']['IP']) {
          do {
            $this->getExternalLog($server['IP'], $ipList['me']['IP']);
          } while ($this->keepCollecting == true);
        }
      }
    }
  }

  private function getExternalLog($ip, $myIP) {
    error_log("MessageCollector: getExternalLog() for " . $ip . " ...");
    $request = new HTTP_Request2('http://' . $ip . '/Architektur-Verteilter-Systeme/a2/getLoggerHTML.php', HTTP_Request2::METHOD_GET);

    try {
      $response = $request->send();

      if (200 == $response->getStatus()) {
        $entryJson = $response->getBody();
        $entryArray = json_decode($entryJson, true);

        $entry = array (
          'from' => $entryArray['message']['from'],
          'message' => $entryArray['message']['message'],
          'timestamp' => $entryArray['message']['timestamp']
        );

        error_log("Try sending message to logger.php:" .
          " from: " . $entry['from'] .
          " message: " . $entry['message'] .
          " timestamp: " . $entry['timestamp'] .
          " ...");

        if (!empty($entry['from']) && !empty($entry['message']) && !empty($entry['timestamp'])) {
          try {
            $request = new HTTP_Request2('http://' . $myIP . '/Architektur-Verteilter-Systeme/a2/logger.php');
            $request->setMethod(HTTP_Request2::METHOD_POST)
              ->addPostParameter(array('from' => $entry['from'],'message' => $entry['message'], 'timestamp' => $entry['timestamp']));
            $request->send()->getBody();
            error_log("Sent message to logger.php.");
          } catch (Exception $exc) {
            echo $exc->getMessage();
          }
        }

        if ($entryArray['more'] < 1) {
          $this->keepCollecting = false;
        }
      } else {
        error_log("MessageCollector: No messages found on " . $ip . ".");
        $this->keepCollecting = false;
      }
    } catch (HTTP_Request2_Exception $e) {
      $this->keepCollecting = false;
    }
  }
}