<?php

require_once 'HTTP/Request2.php';

// TODO PEAR request to that registry server, serialize the registry server IP and the response in ipList.txt

$registryServer = '';
$serverName = '';

if(!empty($_REQUEST['name'] && !empty($_REQUEST['ip']))) {
  $serverName = $_REQUEST['name'];
  $registryServer = $_REQUEST['ip'];
}

$request = new HTTP_Request2('http://' . $registryServer . '/Architektur-Verteilter-Systeme/a2/api/registry.php', HTTP_Request2::METHOD_GET);

$url = $request->getUrl();
$url->setQueryVariable('name', $serverName);

try {
  $response = $request->send();

  if (200 == $response->getStatus()) {
    $ipList = $response->getBody();
    var_dump(http_response_code(200));
    echo('Registry-Server hat Request erhalten und antwortete mit IP-Liste: ' . $ipList);
  } else {
    echo 'Unerwarteter HTTP-Status: ' . $response->getStatus() . ' ' . $response->getReasonPhrase();
    var_dump(http_response_code(404));
    echo('Registry-Server ist nicht erreichbar.');
  }
} catch (HTTP_Request2_Exception $e) {
  echo 'Fehler: ' . $e->getMessage();
}