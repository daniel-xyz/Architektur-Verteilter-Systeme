<?php

require_once('class/Logger.class.php');
require_once('class/MessageCollector.class.php');

$logger = new Logger();
$messageCollector = new MessageCollector();

$messageCollector->collect();
$entry = $logger->getLog();

if (!empty($entry)) {
  $response['message']['time'] = date('d.m.Y \u\m G:i:s', $entry['time']);
  $response['message']['from'] = $entry['from'];
  $response['message']['message'] = $entry['message'];
  $response['more'] = $entry['more'];
  echo json_encode($response);
} else {
  var_dump(http_response_code(404));
}