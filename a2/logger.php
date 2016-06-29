<?php

require_once('class/Logger.class.php');
require_once('class/FileHandler.class.php');

$fileHandler = new FileHandler();

if(!empty($_REQUEST['message']) && !empty($_REQUEST['timestamp']) ) {
  $ipList = $fileHandler->deserialize('persistence/iplist.txt');
  $from = 'default';

  if ($_SERVER['REMOTE_ADDR'] == $_SERVER['SERVER_ADDR']) {
    $from = $ipList['me']['name'];
  } else {
    $from = $ipList['all'][$_SERVER['REMOTE_ADDR']]['name'];
  }

  $entry = array (
    'from' => $from,
    'message' => $_REQUEST['message'],
    'timestamp' => $_REQUEST['timestamp']
  );
  $logger = new Logger();
  $logger->log($entry);
}