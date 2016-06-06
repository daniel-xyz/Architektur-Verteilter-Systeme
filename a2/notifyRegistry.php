<?php

require_once 'HTTP/Request2.php';
require_once 'class/FileHandler.class.php';

// TODO Server should know his own ip somehow

$registryServer = '';
$serverName = '';

if(!empty($_REQUEST['name'] && !empty($_REQUEST['ip']))) {
  $serverName = $_REQUEST['name'];
  $registryServer = $_REQUEST['ip'];

  $request = new HTTP_Request2('http://' . $registryServer . '/Architektur-Verteilter-Systeme/a2/api/registry.php', HTTP_Request2::METHOD_GET);

  $url = $request->getUrl();
  $url->setQueryVariable('name', $serverName);

  try {
    $response = $request->send();

    if (200 == $response->getStatus()) {
      $ipList = $response->getBody();
      $fileHandler = new FileHandler();
      $fileHandler->serialize('persistence/iplist.txt', $ipList);
      echo('Registry-Server hat Request erhalten und antwortete mit IP-Liste. ');
    } else {
      echo 'Unerwarteter HTTP-Status vom Registry-Server: ' . $response->getStatus() . '. ' . $response->getReasonPhrase() . ' ';
    }
  } catch (HTTP_Request2_Exception $e) {
    echo 'Fehler: ' . $e->getMessage();
    var_dump(http_response_code(500));
  } finally {
    var_dump(http_response_code($response->getStatus()));
  }
} else {
  echo('Name und IP dürfen im Request nicht fehlen. ');
  var_dump(http_response_code(409));
}