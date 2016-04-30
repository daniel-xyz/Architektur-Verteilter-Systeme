<?php

require_once('includes.php');

$logger = new Logger();
$entry = $logger->getLog();

if(!empty($entry)) {
  $response['message']['time'] = date('d.m.Y \u\m G:i:s', $entry['time']);
  $response['message']['from'] = $entry['from'];
  $response['message']['message'] = $entry['message'];
  $response['more'] = $entry['more'];

  echo json_encode($response);

} else {
  var_dump(http_response_code(404));
}