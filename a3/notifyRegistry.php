<?php

require_once('HTTP/Request2.php');
require_once('class/FileHandler.class.php');
require_once('class/IPListHandler.class.php');

if(!empty($_REQUEST['name'] && !empty($_REQUEST['ip']))) {
  notifyRegistry($_REQUEST['ip'], $_REQUEST['name']);
}

if(!empty($_REQUEST['yourip'] && !empty($_REQUEST['yourname']))) {
  processRegistryAnswer($_REQUEST['yourip'], $_REQUEST['yourname']);
  var_dump(http_response_code(200));
}

function notifyRegistry($registryServer, $serverName) {
  try {
    $request = new HTTP_Request2('http://' . $registryServer . '/Architektur-Verteilter-Systeme/a3/api/registry.php');
    $request->setMethod(HTTP_Request2::METHOD_POST)
      ->addPostParameter(array('name' => $serverName));
    $request->send();
  } catch (Exception $exc) {
    error_log($exc->getMessage());
  }
}

function processRegistryAnswer($ip, $name) {
  $IPListHandler = new IPListHandler();
  $IPListHandler->setMyIP($ip, $name);
}