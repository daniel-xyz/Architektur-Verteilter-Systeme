<?php

require_once('HTTP/Request2.php');
require_once('../class/IPListHandler.class.php');

$ipListHandler = new IPListHandler();

if (!empty($_REQUEST['name'])) {
  $name = $_REQUEST['name'];
}

if (!empty($_REQUEST['kickip'])) {
  removeFromIpList($_REQUEST['kickip']);
}

if(!empty($_REQUEST['newip'])) {
  $ip = $_REQUEST['newip'];
} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
  $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} elseif(!empty($_SERVER['REMOTE_ADDR'])) {
  $ip = $_SERVER['REMOTE_ADDR'];
} else {
  user_error("IP konnte nicht ermittelt werden.");
}

if (!empty($name) && !empty($ip)) {
  sendInformationToNewServer($name, $ip);
}

function sendInformationToNewServer($name, $ip) {
  $request = new HTTP_Request2('http://' . $ip . '/Architektur-Verteilter-Systeme/a4/notifyRegistry.php', HTTP_Request2::METHOD_GET);

  $url = $request->getUrl();
  $url->setQueryVariable('yourname', $name);
  $url->setQueryVariable('yourip', $ip);

  try {
    $response = $request->send();

    if (200 == $response->getStatus()) {
      addToIpList($name, $ip);
    } else {
      error_log('Unerwarteter HTTP-Status vom Registry-Server: ' . $response->getStatus() . '. ' . $response->getReasonPhrase() . ' ');
    }
  } catch (HTTP_Request2_Exception $e) {
    error_log($e->getMessage());
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
  triggerNeighborNotifications();
}

function removeFromIpList($ip) {
  global $ipListHandler;
  $ipList = $ipListHandler->getList();
  $index = array_search($ip, array_keys($ipList));

  error_log('Wird aus der Registry entfernt: ' . $ipList[$ip]['name'] . ' ' . $ipList[$ip]['ip']);

  unset($ipList[$index]);
  $ipList = array_values($ipList[$ip]);
  $ipListHandler->update($ipList);
  triggerNeighborNotifications();
}

function triggerNeighborNotifications() {
  error_log('Registry stößt die Neighbors-Notifications an.');

  try {
    $request = new HTTP_Request2('http://' . 'localhost' . '/Architektur-Verteilter-Systeme/a4/yourNeighbor.php');
    $request->setMethod(HTTP_Request2::METHOD_POST);
    $request->send();
  } catch (Exception $exc) {
    error_log($exc->getMessage());
  }
}