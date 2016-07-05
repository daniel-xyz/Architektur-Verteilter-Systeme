<?php

require_once('class/Logger.class.php');
require_once('class/IPListHandler.class.php');


$fileHandler = new FileHandler();
$ipListHandler = new IPListHandler();
$myIP = $ipListHandler->getMyIP();
$isClientMessage = false;
$isServerMessage = false;
$isLoopEnd = false;
$sender = 0;

if (!empty($_REQUEST['message']) && !empty($_REQUEST['timestamp'])) {
  $message = $_REQUEST['message'];
  $timestam = $_REQUEST['timestamp'];
}

if (!empty($_REQUEST['sender'])) {
  $isServerMessage = true;
  $sender = $_REQUEST['sender'];
} else {
  $isClientMessage = true;
  $sender = $ipListHandler->getMyName();
}

if ($sender === $myIP) {
  $isLoopEnd = true;
}

if (!$isLoopEnd) {

  $entry = array (
    'sender' => $sender,
    'message' => $_REQUEST['message'],
    'timestamp' => $_REQUEST['timestamp']
  );

  $logger = new Logger();
  $logger->log($entry);

  $ipList = $ipListHandler->getList();
  $nextIP = "";

  if (is_array($ipList) && count($ipList) > 1) {
    $keys = array_keys($ipList);
    $indexOfMyIP = array_search($myIP, array_keys($ipList));
    error_log("sendMessage.php: Index meiner IP in der neuen IP-Liste: " . $indexOfMyIP);

    if ($indexOfMyIP < (count($ipList) - 1)) {
      $neighbor = $ipList[$keys[$indexOfMyIP + 1]];

      if (!empty($neighbor)) {
        error_log("sendMessage.php: Mein nächster Nachbar: " . $neighbor['ip']);
        $nextIP = $neighbor['ip'];
      }
    } else {
      $nextIP = $ipList[$keys[0]]['ip'];
    }

    try {
      $request = new HTTP_Request2('http://' . $nextIP . '/Architektur-Verteilter-Systeme/a3/sendMessage.php');
      $request->setMethod(HTTP_Request2::METHOD_POST)
        ->addPostParameter(array('message' => $_REQUEST['message'], 'timestamp' => $_REQUEST['timestamp'], 'sender' => $sender));
      $request->send();
      error_log("sendMessage.php: von " . $nextIP . " wurde aufgerufen.");
    } catch (Exception $exc) {
      echo $exc->getMessage();
      error_log("sendMessage.php: " . $nextIP . " konnte nicht aufgerufen werden.");
    }
  }
} else {
  error_log("sendMessage.php: wurde fertig ausgeführt!");
}