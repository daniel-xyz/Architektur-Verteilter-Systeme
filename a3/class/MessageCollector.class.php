<?php

require_once 'HTTP/Request2.php';
require_once('IPListHandler.class.php');

class MessageCollector {

  private $ipListHandler;
  private $ipList;
  private $keepCollecting = true;

  function __construct() {
    $this->ipListHandler = new IPListHandler();
    $this->ipList = $this->ipListHandler->getList();;
  }

  public function collect() {
    if (is_array($this->ipList) && array_key_exists('all', $this->ipList) && count($this->ipList['all']) > 0) {
      foreach ($this->ipList['all'] as $server) {
        $this->keepCollecting = true;

        if ($server['IP'] != $this->ipListHandler->getMyIP()) {
          do {
            $this->getExternalLog($server);
          } while ($this->keepCollecting == true);
        }
      }
    }
    error_log("No other servers registered.");
  }

  private function getExternalLog($server) {
    error_log("MessageCollector: getExternalLog() for " . $server['IP'] . " ...");
    $request = new HTTP_Request2('http://' . $server['IP'] . '/Architektur-Verteilter-Systeme/a3/getLoggerHTML.php', HTTP_Request2::METHOD_GET);

    try {
      $response = $request->send();

      if (200 == $response->getStatus()) {
        $responseJson = $response->getBody();
        $responseArray = json_decode($responseJson, true);

        $entry = array (
          'from' => $server['name'],
          'message' => $responseArray['message']['message'],
          'timestamp' => $responseArray['message']['timestamp']
        );

        error_log("Try sending message to logger.php:" .
          " from: " . $server['name'] .
          " message: " . $entry['message'] .
          " timestamp: " . $entry['timestamp'] .
          " ...");

        if (!empty($entry['from']) && !empty($entry['message']) && !empty($entry['timestamp'])) {
          try {
            $request = new HTTP_Request2('http://' . $this->ipListHandler->getMyIP() . '/Architektur-Verteilter-Systeme/a3/logger.php');
            $request->setMethod(HTTP_Request2::METHOD_POST)
              ->addPostParameter(array('from' => $entry['from'],'message' => $entry['message'], 'timestamp' => $entry['timestamp']));
            $request->send();
            error_log("Sent message to logger.php.");
          } catch (Exception $exc) {
            echo $exc->getMessage();
          }
        }

        if ($responseArray['more'] < 1) {
          $this->keepCollecting = false;
        }
      } else {
        error_log("MessageCollector: No messages found on " . $server['IP'] . ".");
        $this->keepCollecting = false;
      }
    } catch (HTTP_Request2_Exception $e) {
      $this->keepCollecting = false;
    }
  }
}