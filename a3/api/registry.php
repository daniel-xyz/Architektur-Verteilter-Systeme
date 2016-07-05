<?php

require_once('HTTP/Request2.php');
require_once('../class/IPListHandler.class.php');

$ipListHandler = new IPListHandler();

if (!empty($_REQUEST['name'])) {
  $name = $_REQUEST['name'];

  if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    addToIpList($name, $ip);
    triggerNeighborNotifications();
} elseif(!empty($_SERVER['REMOTE_ADDR'])) {
    $ip = $_SERVER['REMOTE_ADDR'];
    addToIpList($name, $ip);
    triggerNeighborNotifications();
  } else {
    user_error("IP konnte nicht ermittelt werden.");
  }
}

function addToIpList($name, $ip) {
  global $ipListHandler;
  $ipList = $ipListHandler->getList();

  $ipList[$ip] = array(
    'name' => $name,
    'ip' => $ip
  );

  error_log('Server registriert: ' . $ipList[$ip]['name'] . ' ' . $ipList[$ip]['ip']);

  $ipListHandler->update($ipList);

  $yourIP = array(
    'name' => $name,
    'ip' => $ip
  );

  error_log('Registry: Sende neuem Server seine Daten: ' . $yourIP['name'] . ' ' . $yourIP['ip']);

  echo json_encode($yourIP);
}

function triggerNeighborNotifications() {
  try {
    $request = new HTTP_Request2('http://' . $_SERVER['SERVER_ADDR'] . '/Architektur-Verteilter-Systeme/a3/yourNeighbor.php');
    $request->setMethod(HTTP_Request2::METHOD_POST);
    $request->send();
  } catch (Exception $exc) {
    error_log($exc->getMessage()); // TODO ﻿Unable to connect to tcp://:80. Error: php_network_getaddresses: getaddrinfo failed:
  }
}