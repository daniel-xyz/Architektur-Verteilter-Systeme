<?php

require_once('IPListHandler.class.php');
require_once('HTTP/Request2.php');

class ServerRestarter {

  private $ipListHandler;

  function __construct() {
    $this->ipListHandler = new IPListHandler();
  }

  public function restartAllServers() {
    $ipList = $this->ipListHandler->getList();

    if (is_array($ipList) && array_key_exists('all', $ipList) && count($ipList['all']) > 0) {
      foreach ($ipList['all'] as $server) {
        if ($server['ip'] != $this->ipListHandler->getMyIP()) {
          $this->restart($server['ip']);
        }
      }
    }
  }

  private function restart($ip) {
    try {
      $request = new HTTP_Request2('http://' . $ip . '/Architektur-Verteilter-Systeme/a4/restart.php');
      $request->setMethod(HTTP_Request2::METHOD_POST);
      $request->send();
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }
}