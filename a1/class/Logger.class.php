<?php

class Logger {

  private  $fileName = 'messages.txt';

  public function log($message) {
    $entries = $this->deserialize();
    array_push($entries, $message);
    $this->serialize($entries);
    print_r($entries); // Nur zum Debuggen, um das Array im Browser in der Response zu sehen.
  }

  public function getLog() {
    return $this->deserialize();
  }

  public function getDateSortedLog() {
    $entries = $this->getLog();

    usort($entries, function ($a, $b) {
      $t1 = strtotime($a['time']);
      $t2 = strtotime($b['time']);
      return $t2 - $t1;
    });

    return $entries;
  }

  private function serialize($entries) {
    if(file_exists($this->fileName) && (count($entries) > 0)) {
      file_put_contents($this->fileName, serialize($entries));
    }
  }

  private function deserialize() {
    $entries = array();
    if(file_exists($this->fileName)) {
      $data = file_get_contents($this->fileName);

      if(strlen($data) > 0) {
        $entries = unserialize($data);
      }
    }
    return $entries;
  }
}