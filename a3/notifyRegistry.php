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

    if (200 == $response->getStatus()) {
      $body = $response->getBody();
      $myIP = json_decode($body, true);

      error_log("Server meldet mir meine IP: " . $body);

      $IPListHandler->setMyIP($myIP['ip'], $myIP['name']);
      echo json_encode($response);
    } else {
      echo 'Unerwarteter HTTP-Status vom Registry-Server: ' . $response->getStatus() . '. ' . $response->getReasonPhrase() . ' ';
    }
  } catch (HTTP_Request2_Exception $e) {
    echo 'Fehler: ' . $e->getMessage();
  }
}