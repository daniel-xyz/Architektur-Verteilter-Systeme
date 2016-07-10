<?php

require_once('HTTP/Request2.php');
require_once('class/FileHandler.class.php');
require_once('class/IPListHandler.class.php');

if(!empty($_REQUEST['registryip'] && !empty($_REQUEST['name']))) {
  notifyExternalRegistry($_REQUEST['registryip'], $_REQUEST['name']);
}

if(!empty($_REQUEST['newip'] && !empty($_REQUEST['name']))) {
  notifyInternalRegistry($_REQUEST['newip'], $_REQUEST['name']);
}

if(!empty($_REQUEST['yourip'] && !empty($_REQUEST['yourname']))) {
  error_log('Antwort der Registry: ' . $_REQUEST['yourname'] . ' ' . $_REQUEST['yourip']);
  processRegistryAnswer($_REQUEST['yourip'], $_REQUEST['yourname']);
  var_dump(http_response_code(200));
}

function notifyExternalRegistry($registryServer, $serverName) {
  try {
    $request = new HTTP_Request2('http://' . $registryServer . '/Architektur-Verteilter-Systeme/a4/api/registry.php');
    $request->setMethod(HTTP_Request2::METHOD_POST)
      ->addPostParameter(array('name' => $serverName));
    $request->send();
  } catch (Exception $exc) {
    error_log($exc->getMessage());
  }
}

function notifyInternalRegistry($newIP, $serverName) {
  try {
    $request = new HTTP_Request2('http://' . 'localhost' . '/Architektur-Verteilter-Systeme/a4/api/registry.php');
    $request->setMethod(HTTP_Request2::METHOD_POST)
      ->addPostParameter(array('newip' => $newIP, 'name' => $serverName));
    $request->send();
  } catch (Exception $exc) {
    error_log($exc->getMessage());
  }
}

function processRegistryAnswer($ip, $name) {
  $IPListHandler = new IPListHandler();
  $IPListHandler->setMyIP($ip, $name);
}