<?php

require_once('class/Logger.class.php');
require_once('class/FileHandler.class.php');

$fileHandler = new FileHandler();

if(!empty($_REQUEST['message']) && !empty($_REQUEST['timestamp'])) {
  $ipList = $fileHandler->deserialize('iplist.txt');
  $from = 'default';

  // Nachricht kommt von einem anderen Server
  if(!empty($_REQUEST['from'])) {
    $from = $_REQUEST['from'];
  } else if (array_key_exists('me', $ipList)) {
    $from = $ipList['me']['name'];
  }

  $entry = array (
    'from' => $from,
    'message' => $_REQUEST['message'],
    'timestamp' => $_REQUEST['timestamp']
  );

  $logger = new Logger();
  $logger->log($entry);
}