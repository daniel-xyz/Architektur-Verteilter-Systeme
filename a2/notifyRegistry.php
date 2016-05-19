<?php

require_once 'HTTP/Request2.php';

// TODO PEAR request to that registry server, serialize the registry server IP and the response in ipList.txt

$registryServer = '';

if(!empty($_REQUEST['ip'])) {
  $registryServer = ip;
}

$request = new HTTP_Request2($registryServer, HTTP_Request2::METHOD_GET);

try {
  $response = $request->send();

  if (200 == $response->getStatus()) {
    echo $response->getBody();
  } else {
    echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
      $response->getReasonPhrase();
  }
} catch (HTTP_Request2_Exception $e) {
  echo 'Error: ' . $e->getMessage();
}