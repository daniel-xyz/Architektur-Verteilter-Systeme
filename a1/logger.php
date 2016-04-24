<?php

if(!empty($_REQUEST['time']) && !empty($_REQUEST['from']) && !empty($_REQUEST['message'])) {
  $messages = array();
  array_push($messages, $_REQUEST['time'], $_REQUEST['from'], $_REQUEST['message']);

  print_r($messages); // Nur zum Debuggen, um das Array im Browser in der Response zu sehen.
}