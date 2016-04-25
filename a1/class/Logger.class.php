<?php

class Logger {

  private $fileName = 'messages.txt';

  public function log($message) {
    $entries = array();
    $fileName = $this->fileName;

    if(file_exists($fileName)) {
      $data = file_get_contents($fileName);

      if(strlen($data) > 0) {
        $entries = unserialize($data);
      }
    }

    $entries[] = $message;
    file_put_contents($fileName, serialize($entries));
    print_r($entries); // Nur zum Debuggen, um das Array im Browser in der Response zu sehen.
  }
}