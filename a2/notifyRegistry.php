<?php

require_once 'HTTP/Request2.php';

// TODO PEAR request to that registry server, serialize the registry server IP and the response in ipList.txt

$registryServer = '';

if(!empty($_REQUEST['ip'])) {
  $registryServer = ip;
}

$request = new HTTP_Request2($registryServer . '/Architektur-Verteilter-Systeme/a2/service/registry.php', HTTP_Request2::METHOD_POST);

try {
  $response = $request->send();

  if (200 == $response->getStatus()) {
    //echo $response->getBody();
    var_dump(http_response_code(200));
  } else {
    echo 'Unerwarteter HTTP-Status: ' . $response->getStatus() . ' ' . $response->getReasonPhrase();
    var_dump(http_response_code(404));
  }
} catch (HTTP_Request2_Exception $e) {
  echo 'Fehler: ' . $e->getMessage();
}