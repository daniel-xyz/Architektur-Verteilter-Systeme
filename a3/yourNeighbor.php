<?php

require_once('HTTP/Request2.php');
require_once('class/IPListHandler.class.php');

$ipListHandler = new IPListHandler();
$ipList = array();
$myIP = $_SERVER['SERVER_ADDR'];
$initiator = "";
$isInitiator = false;
$loopActive = false;

if (!empty($_REQUEST['initiator']) && !empty($_REQUEST['iplist'])) {
  $initiator = $_REQUEST['initiator'];
  $ipList = $_REQUEST['iplist'];
}

if (empty($_REQUEST['initiator'])) {
  $ipList = $ipListHandler->getList();
  $initiator = $myIP;
  $isInitiator = true;
  $loopActive = true;
  error_log("yourNeughbor.php: Iniitiert von " . $initiator);
} else if ($initiator === $myIP) {
  $isInitiator = true;
  $loopActive = false;
}

if(!$isInitiator) {
  $ipListHandler->update($ipList);
}

if($loopActive) {
  $nextIP = $ipListHandler->getMyNextNeighborsIPFromTemporaryList($ipList, $myIP);

  if ($nextIP !== $myIP) {

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

  try {
    $request = new HTTP_Request2('http://' . $myIP . '/Architektur-Verteilter-Systeme/a3/sendMessage.php');
    $request->setMethod(HTTP_Request2::METHOD_POST)
      ->addPostParameter(array('message' => 'Neuer Server wurde erfolgreich angemeldet!' , 'timestamp' => time(), 'system' => true));
    $request->send();
    error_log("yourNeighbor.php: $myIP versendet Systemnachricht.");
  } catch (Exception $exc) {
    echo $exc->getMessage();
    error_log("yourNeighbor.php: $myIP konnte Systemnachricht nicht versenden.");
  }
}