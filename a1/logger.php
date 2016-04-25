<?php

class Logger {

  static $messages = array();

  public static function log($message) {
    array_push(self::$messages, $message);
    print_r(self::$messages); // Nur zum Debuggen, um das Array im Browser in der Response zu sehen.
  }
}