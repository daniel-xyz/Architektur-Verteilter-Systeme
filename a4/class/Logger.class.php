<?php

require_once('FileHandler.class.php');

class Logger {

  private $fileName = 'messages.txt';

  public function log($entry) {
    $fileHandler = new FileHandler();

    $entries = $fileHandler->deserialize($this->fileName);
    $entries[$entry['timestamp']] = $entry;
    $fileHandler->serialize($this->fileName, $entries);
    print_r($entries); // Nur zum Debuggen, um das Array im Browser in der Response zu sehen
  }

  public function getLog() {
    $fileHandler = new FileHandler();

    $oldestEntry = array();
    $entries = $fileHandler->deserialize($this->fileName);

    if (count($entries) > 0) {
      krsort($entries);
      $oldestEntry = array_pop($entries);
      $oldestEntry['more'] = count($entries);
      $fileHandler->serialize($this->fileName, $entries);
    }

    return $oldestEntry;
  }

  public function resetLog() {
    $fileHandler = new FileHandler();
    $fileHandler->emptyFile($this->fileName);
  }
}