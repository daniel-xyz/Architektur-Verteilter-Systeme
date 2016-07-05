<?php

require_once('HTTP/Request2.php');
require_once('class/FileHandler.class.php');
require_once('class/IPListHandler.class.php');

$ipListHandler = new IPListHandler();
$ipList = array();
$myIP = $_SERVER['SERVER_ADDR'];
$initiator = "";
$isInitiator = false;
$isLoopStart = false;
$isLoopEnd = false;

if (!empty($_REQUEST['initiator']) && !empty($_REQUEST['iplist'])) {
  $initiator = $_REQUEST['initiator'];
  $ipList = $_REQUEST['iplist'];
}

if (empty($_REQUEST['initiator'])) {
  $ipList = $ipListHandler->getList();
  $initiator = $myIP;
  $isInitiator = true;
  $isLoopStart = true;
  error_log("yourNeughbor.php: Iniitiert von " . $initiator);
}

if ($initiator === $myIP) {
  $isInitiator = true;
  $isLoopEnd = true;
}

if(!$isInitiator) {
  $ipListHandler->update($ipList);
}

if(!$isLoopEnd) {
  $nextIP = "";

  if (is_array($ipList) && count($ipList) > 1) {
    $keys = array_keys($ipList);
    $indexOfMyIP = array_search($myIP, array_keys($ipList));
    error_log("Index meiner IP in der neuen IP-Liste: " . $indexOfMyIP);

    if ($indexOfMyIP < (count($ipList) - 1)) {
      $neighbor = $ipList[$keys[$indexOfMyIP + 1]];

      if (!empty($neighbor)) {
        error_log("Mein nächster Nachbar: " . $neighbor['ip']);
        $nextIP = $neighbor['ip'];
      }
    } else {
      $nextIP = $ipList[$keys[0]]['ip'];
    }

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
} else {
  error_log("yourNeighbor.php wurde fertig ausgeführt!");
}