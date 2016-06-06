<?php

class Logger {

  private  $fileName = 'messages.txt';

  public function log($message) {
    $entries = $this->deserialize();
    $entries[$message['time']] = $message;
    $this->serialize($entries);
    print_r($entries); // Nur zum Debuggen, um das Array im Browser in der Response zu sehen.
  }

  public function getLog() {
    $oldestEntry = array();
    $entries = $this->deserialize();

    if (count($entries) > 0) {
      krsort($entries);
      $oldestEntry = array_pop($entries);
      $oldestEntry['more'] = count($entries);
      $this->serialize($entries);
    }

    return $oldestEntry;
  }

  public function resetLog() {
    $file = fopen($this->fileName, "r+");

    if (flock($file, LOCK_EX)) { // exklusive Sperre
      ftruncate($file, 0); // kürze Datei
      fflush($file); // leere Ausgabepuffer bevor die Sperre frei gegeben wird
      flock($file, LOCK_UN); // Gib Sperre frei
    }

    fclose($file);
  }

  private function serialize($entries) {
    $file = fopen($this->fileName, "r+");

    if (flock($file, LOCK_EX)) { // exklusive Sperre
      ftruncate($file, 0); // kürze Datei
      fwrite($file, serialize($entries));
      fflush($file); // leere Ausgabepuffer bevor die Sperre frei gegeben wird
      flock($file, LOCK_UN); // Gib Sperre frei
    }

    fclose($file);
  }

  private function deserialize() {
    $entries = array();
    $file = fopen($this->fileName, "r");

    if (flock($file, LOCK_SH)) { // geteilte Sperre
      $fileSize = filesize($this->fileName);

      if ($fileSize > 0) {
        $entries = unserialize(fread($file, $fileSize));
      } else {
        print_r("Kein Dateiinhalt zum Deserialisieren vorhanden.");
      }
      fflush($file); // leere Ausgabepuffer bevor die Sperre frei gegeben wird
      flock($file, LOCK_UN); // Gib Sperre frei
    }

    fclose($file);

    return $entries;
  }
}