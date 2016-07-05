<?php

require_once('FileHandler.class.php');
require_once 'HTTP/Request2.php';

class ServerRestarter {

  private $fileHandler;

  function __construct() {
    $this->fileHandler = new FileHandler();
  }

  public function restartAllServers() {
    $ipList = $this->fileHandler->deserialize('persistence/iplist.txt');

    if (is_array($ipList) && array_key_exists('all', $ipList) && count($ipList['all']) > 0) {
      foreach ($ipList['all'] as $server) {
        if ($server['IP'] != $ipList['me']['IP']) {
          $this->restart($server['IP']);
        }
      }
    }
  }

  private function restart($ip) {
    try {
      $request = new HTTP_Request2('http://' . $ip . '/Architektur-Verteilter-Systeme/a3/restart.php');
      $request->setMethod(HTTP_Request2::METHOD_POST);
      $request->send();
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }
}