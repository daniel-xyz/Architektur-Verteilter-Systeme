<?php

// TODO Takes requests, and if IP is not saved it saves the it with a random name in ipList.txt
// TODO Responds with deserliazed ipList.txt

$newIP = '';

if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
  $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} elseif(!empty($_SERVER['REMOTE_ADDR'])) {
  $ip = $_SERVER['REMOTE_ADDR'];
} else {
  user_error("IP konnte nicht ermittelt werden.");
}