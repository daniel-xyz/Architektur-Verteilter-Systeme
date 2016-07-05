<?php

require_once('../class/FileHandler.class.php');

if (!empty($_REQUEST['name'])) {
  $name = $_REQUEST['name'];

  if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
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
  $fileName = '../persistence/iplist.txt';
  $fileHandler = new FileHandler();

  $ipList = $fileHandler->deserialize($fileName);

  if (!is_array($ipList)) {
    $ipList = array();
  }

  $ipList['all'][$ip] = array(
    'name' => $name,
    'IP' => $ip
  );

  $fileHandler->serialize($fileName, $ipList);

  $ipList['me'] = array(
    'name' => $name,
    'IP' => $ip
  );

  if (count($ipList) > 0) {
    echo json_encode($ipList);
  }
}

// TODO a new function to notify all servers when a new server was added to the registry