<?php

require_once('includes.php');

if(!empty($_REQUEST['time']) && !empty($_REQUEST['from']) && !empty($_REQUEST['message'])) {
  $message = array
  (
    'time' => $_REQUEST['time'],
    'from' => $_REQUEST['from'],
    'message' => $_REQUEST['message']
  );
  $logger = new Logger();
  $logger->log($message);
}