<?php

require_once('includes.php');

$logger = new Logger();
$entries = $logger->getLog();

usort($entries, function ($a, $b) {
  $t1 = strtotime($a['time']);
  $t2 = strtotime($b['time']);
  return $t2 - $t1;
});

foreach($entries as $key => $entry) {
  $entry['time'] = date('d.m.Y \u\m G:i:s', $entry['time']);
  $entries[$key]['time'] = $entry['time'];
}

echo json_encode($entries[0]);