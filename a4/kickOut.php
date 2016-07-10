<?php

require_once('HTTP/Request2.php');
require_once('class/IPListHandler.class.php');

$IPListHandler = new IPListHandler();
$myIP = $IPListHandler->getMyIP();

error_log("Ich wurde aus dem Chat gekickt!");

try {
  $request = new HTTP_Request2('http://' . 'localhost' . '/Architektur-Verteilter-Systeme/a4/api/registry.php');
  $request->setMethod(HTTP_Request2::METHOD_POST)
    ->addPostParameter(array('kickip' => $myIP));
  $request->send();
} catch (Exception $exc) {
  error_log($exc->getMessage());
}