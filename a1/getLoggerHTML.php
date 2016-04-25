<?php

require_once('includes.php');

$logger = new Logger();
$entries = $logger->getDateSortedLog();
echo json_encode($entries[0]);