<?php

require_once('includes.php');

$logger = new Logger();
$entry = $logger->getLog();
$entryForeign['1462372798']['message'] = array(
  'time' => 1462372798,
  'from' => 'Daniel',
  'message' => 'Hier könnte eine interessantere Nachricht stehen.'
);
$entryForeign['1462372798']['more'] = 0;
$allEntries[] = $entry;
$allEntries[] = $entryForeign;

krsort($allEntries);

if(!empty($allEntries)) {
  foreach($allEntries as $key => $entry) {
    $response[$key]['message']['time'] = date('d.m.Y \u\m G:i:s', $entry['time']);
    $response[$key]['message']['from'] = $entry['from'];
    $response[$key]['message']['message'] = $entry['message'];
    $response[$key]['more'] = $entry['more'];
  }

  echo json_encode($response);

} else {
  var_dump(http_response_code(404));
}