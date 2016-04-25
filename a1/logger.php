<?php

class Logger {

  static $fileName = 'messages.txt';
  static $messages = array();

  public static function log($message) {
    if(file_exists(self::$fileName)) {
      $data = file_get_contents(self::$fileName);

      if(strlen($data) > 0) {
        self::$messages = unserialize($data);
      }
    }

    self::$messages[] = $message;

    file_put_contents(self::$fileName, serialize(self::$messages));

    print_r(self::$messages); // Nur zum Debuggen, um das Array im Browser in der Response zu sehen.
  }
}