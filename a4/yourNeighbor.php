<?php

require_once('HTTP/Request2.php');
require_once('class/IPListHandler.class.php');

$ipListHandler = new IPListHandler();
$ipList = array();
$myIP = $ipListHandler->getMyIP();
$initiator = "";
$isInitiator = false;
$loopActive = false;

if (isset($_REQUEST['initiator']) && isset($_REQUEST['iplist'])) {
  $initiator = $_REQUEST['initiator'];
  $ipList = $_REQUEST['iplist'];
}

if (empty($_REQUEST['initiator'])) {
  $ipList = $ipListHandler->getList();
  $initiator = $myIP;
  $isInitiator = true;
  $loopActive = true;
} else if ($initiator === $myIP) {
  $isInitiator = true;
  $loopActive = false;
} else {
  $loopActive = true;
}

if(!$isInitiator) {
  $ipListHandler->update($ipList);
}

if($loopActive) {
  $nextIP = $ipListHandler->getMyNextNeighborsIPFromTemporaryList($ipList, $myIP);

  if ($nextIP !== $myIP) {
    try {
      $request = new HTTP_Request2('http://' . $nextIP . '/Architektur-Verteilter-Systeme/a4/yourNeighbor.php');
      $request->setMethod(HTTP_Request2::METHOD_POST)
        ->addPostParameter(array('iplist' => $ipList, 'initiator' => $initiator));
      $request->send();
    } catch (Exception $exc) {
      echo $exc->getMessage();
    }
  }
} else {
  error_log("yourNeighbor.php wurde fertig ausgeführt!");

  try {
    $request = new HTTP_Request2('http://' . $myIP . '/Architektur-Verteilter-Systeme/a4/sendMessage.php');
    $request->setMethod(HTTP_Request2::METHOD_POST)
      ->addPostParameter(array('message' => 'Die Anzahl der Teilnehmer hat sich verändert!' , 'timestamp' => time(), 'system' => 'true'));
    $request->send();
  } catch (Exception $exc) {
    echo $exc->getMessage();
    error_log("yourNeighbor.php: $myIP konnte Systemnachricht nicht versenden.");
  }
}