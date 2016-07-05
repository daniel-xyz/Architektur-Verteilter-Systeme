<?php

require_once('HTTP/Request2.php');
require_once('class/FileHandler.class.php');
require_once('class/IPListHandler.class.php');

$ipListHandler = new IPListHandler();
$ipList = $ipListHandler->getList();
$myIP = $ipListHandler->getMyIP();
$initiator = "";

if(empty($_REQUEST['initiator'])) {
  $initiator = $_SERVER['SERVER_ADDR'];
  error_log("yourNeughbor.php: Iniitiert von " . $initiator);
}

if(empty($_REQUEST['initiator']) || $_REQUEST['initiator'] !== $myIP) {
  if (is_array($ipList) && count($ipList) > 1) {
    $nextIP = $ipListHandler->getMyNextNeighborsIP();

    try {
      $request = new HTTP_Request2('http://' . $nextIP . '/Architektur-Verteilter-Systeme/a3/yourNeighbor.php');
      $request->setMethod(HTTP_Request2::METHOD_POST)
        ->addPostParameter(array('iplist' => $ipList, 'initiator' => $initiator));
      $request->send();
      error_log("yourNeughbor.php von " . $nextIP . " wurde aufgerufen.");
    } catch (Exception $exc) {
      echo $exc->getMessage();
      error_log("yourNeughbor.php von " . $nextIP . " konnte nicht aufgerufen werden.");
    }
  }
}


