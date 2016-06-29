<?php

require_once('class/Logger.class.php');
require_once('class/MessageCollector.class.php');

$logger = new Logger();
$messageCollector = new MessageCollector();

if ($_SERVER['REMOTE_ADDR'] == $_SERVER['SERVER_ADDR']) {
  error_log("getLoggerHTML.php: Request from same ip (remote: " . $_SERVER['REMOTE_ADDR'] . ", local: " . $_SERVER['SERVER_ADDR'] . ", therefore start collecting messages from other servers.");
  $messageCollector->collect();
}

$entry = $logger->getLog();

if (!empty($entry)) {
  $response['message']['time'] = date('d.m.Y \u\m G:i:s', $entry['time']);
  $response['message']['from'] = $entry['from'];
  $response['message']['message'] = $entry['message'];
  $response['more'] = $entry['more'];
  http_response_code(200);
  echo json_encode($response);
} else {
  http_response_code(404);
}