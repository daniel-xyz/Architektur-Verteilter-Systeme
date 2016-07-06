<?php

require_once('class/Logger.class.php');

$logger = new Logger();

$entry = $logger->getLog();

if (!empty($entry) && count($entry) == 4) {
  $response['message']['time'] = date('d.m.Y \u\m G:i:s', $entry['timestamp']);
  $response['message']['sender'] = $entry['sender'];
  $response['message']['message'] = $entry['message'];
  $response['message']['timestamp'] = $entry['timestamp'];
  $response['more'] = $entry['more'];
  http_response_code(200);
  echo json_encode($response);
} else {
  error_log("getLoggerHTML.php: Got no messages from Logger.");
  http_response_code(404);
}