<?php

require_once('classLoader.php');

if(!empty($_REQUEST['time']) && !empty($_REQUEST['from']) && !empty($_REQUEST['message'])) {
  $message = array($_REQUEST['time'], $_REQUEST['from'], $_REQUEST['message']);
  Logger::log($message);
}