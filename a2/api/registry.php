<?php

require_once('../class/FileHandler.class.php');

// TODO Responds with deserliazed iplist.txt

if(!empty($_REQUEST['name'])) {
  $name = $_REQUEST['name'];

  if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    addToIpList($name, $ip);
} elseif(!empty($_SERVER['REMOTE_ADDR'])) {
    $ip = $_SERVER['REMOTE_ADDR'];
    addToIpList($name, $ip);
  } else {
    user_error("IP konnte nicht ermittelt werden.");
  }
}

function addToIpList($name, $ip) {
  $fileName = 'iplist.txt';
  $fileHandler = new FileHandler();

  $ipList = $fileHandler->deserialize($fileName);
  $ipList[] = array (
    'Name' => $name,
    'IP' => $ip
  );
  $fileHandler->serialize($fileName, $ipList);

  echo json_encode($ipList);
}