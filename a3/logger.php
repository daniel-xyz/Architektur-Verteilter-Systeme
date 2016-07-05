<?php

require_once('class/Logger.class.php');
require_once('class/FileHandler.class.php');

$fileHandler = new FileHandler();

if(!empty($_REQUEST['message']) && !empty($_REQUEST['timestamp'])) {
  $ipList = $fileHandler->deserialize('iplist.txt');
  $sender = 'default';

  // Nachricht kommt von einem anderen Server
  if(!empty($_REQUEST['sender'])) {
    $sender = $_REQUEST['sender'];
  } else if (array_key_exists('me', $ipList)) {
    $sender = $ipList['me']['name'];
  }

  $entry = array (
    'sender' => $sender,
    'message' => $_REQUEST['message'],
    'timestamp' => $_REQUEST['timestamp']
  );

  $logger = new Logger();
  $logger->log($entry);
}