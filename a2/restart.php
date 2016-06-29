<?php

require_once('class/Logger.class.php');
require_once('class/ServerRestarter.class.php');

$logger = new Logger();
$restarter = new ServerRestarter();

if ($_SERVER['REMOTE_ADDR'] == $_SERVER['SERVER_ADDR']) {
  $restarter->restartAllServers();
}

$entries = $logger->resetLog();