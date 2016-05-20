<?php

require_once('../class/FileHandler.class.php');

// TODO Responds with deserliazed ipList.txt

$fileName = 'ipList.txt';

if(!empty($_REQUEST['name'])) {
  $name = $this->$name = $_REQUEST['name'];

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
  $fileHandler = new FileHandler();

  $IP_List = $fileHandler->deserialize($this->fileName);
  $IP_List[] = array (
    'Name' => $name,
    'IP' => $ip
  );
  $fileHandler->serialize($this->fileName, $IP_List);

  echo json_encode($IP_List);
}