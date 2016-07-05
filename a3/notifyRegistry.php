<?php

require_once 'HTTP/Request2.php';
require_once 'class/FileHandler.class.php';
require_once 'class/IPListHandler.class.php';

$IPListHandler = new IPListHandler();
$registryServer = '';
$serverName = '';

if(!empty($_REQUEST['name'] && !empty($_REQUEST['ip']))) {
  $serverName = $_REQUEST['name'];
  $registryServer = $_REQUEST['ip'];

  $request = new HTTP_Request2('http://' . $registryServer . '/Architektur-Verteilter-Systeme/a3/api/registry.php', HTTP_Request2::METHOD_GET);

  $url = $request->getUrl();
  $url->setQueryVariable('name', $serverName);

  try {
    $response = $request->send();

    $responseJson = $response->getBody();
      $responseArray = json_decode($responseJson, true);

      error_log("Server meldet mir meine IP: " . $responseArray['ip']);
      $IPListHandler->setMyIP($responseArray['ip'], $responseArray['name']);
      echo json_encode($response);
  } catch (HTTP_Request2_Exception $e) {
    echo 'Fehler: ' . $e->getMessage();
  }
}