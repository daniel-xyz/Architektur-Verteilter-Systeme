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

if(!empty($_REQUEST['initiator']) && $_REQUEST['initiator'] !== $myIP && !empty($_REQUEST['iplist'])) {
  $ipList = $_REQUEST['iplist'];
  $ipListHandler->update($ipList);
}

if(empty($_REQUEST['initiator']) || $_REQUEST['initiator'] !== $myIP) {

  $nextIP = "";

  if (is_array($ipList) && count($ipList) > 1) {
    $keys = array_keys($ipList);
    $indexOfMyIP = array_search($_SERVER['SERVER_ADDR'], array_keys($ipList));
    error_log("Index meiner IP in der neuen IP-Liste: " . $indexOfMyIP);

    if ($indexOfMyIP < count($ipList)) {
      $neighbor = $ipList[$keys[$indexOfMyIP + 1]];

      if (!empty($neighbor)) {
        error_log("Mein nächster Nachbar: " . $neighbor['ip']);
        $nextIP = $neighbor['ip'];
      }
    } else {
      $nextIP = $ipList[$keys[0]];
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
}

if(!empty($_REQUEST['initiator']) && $_REQUEST['initiator'] === $myIP) {
  error_log("yourNeighbor.php wurde fertig ausgeführt!");
}