<?php

require_once('class/Logger.class.php');

if(!empty($_REQUEST['from']) && !empty($_REQUEST['message']) && !empty($_REQUEST['timestamp']) ) {
  $entry = array (
    'from' => $_REQUEST['from'],
    'message' => $_REQUEST['message'],
    'timestamp' => $_REQUEST['timestamp']
  );
  $logger = new Logger();
  $logger->log($entry);
}