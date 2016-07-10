<?php

require_once('HTTP/Request2.php');
require_once('../class/IPListHandler.class.php');

$ipListHandler = new IPListHandler();

if (!empty($_REQUEST['name'])) {
  $name = $_REQUEST['name'];

  if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    addToIpList($name, $ip);
    sendInformationToNewServer($name, $ip);
} elseif(!empty($_SERVER['REMOTE_ADDR'])) {
    $ip = $_SERVER['REMOTE_ADDR'];
    addToIpList($name, $ip);
    sendInformationToNewServer($name, $ip);
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

  error_log('Server in der Registry registriert: ' . $ipList[$ip]['name'] . ' ' . $ipList[$ip]['ip']);

  $ipListHandler->update($ipList);
}

function sendInformationToNewServer($name, $ip) {
  $request = new HTTP_Request2('http://' . $ip . '/Architektur-Verteilter-Systeme/a4/notifyRegistry.php', HTTP_Request2::METHOD_GET);

  $url = $request->getUrl();
  $url->setQueryVariable('yourname', $name);
  $url->setQueryVariable('yourip', $ip);

  try {
    $response = $request->send();

    if (200 == $response->getStatus()) {
      triggerNeighborNotifications();
    } else {
      echo 'Unerwarteter HTTP-Status vom Registry-Server: ' . $response->getStatus() . '. ' . $response->getReasonPhrase() . ' ';
    }
  } catch (HTTP_Request2_Exception $e) {
    error_log($e->getMessage());
  }
}

function triggerNeighborNotifications() {
  error_log('Registry stößt die Neighbors-Notifications an: ' . $_SERVER['SERVER_ADDR']);

  try {
    $request = new HTTP_Request2('http://' . $_SERVER['SERVER_ADDR'] . '/Architektur-Verteilter-Systeme/a4/yourNeighbor.php');
    $request->setMethod(HTTP_Request2::METHOD_POST);
    $request->send();
  } catch (Exception $exc) {
    error_log($exc->getMessage());
  }
}