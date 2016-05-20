<?php

class FileHandler {

  private $defaultFilePath = '../persistence/';

  public function serialize($fileName, $content) {
    $file = fopen($this->defaultFilePath . $fileName, "r+");

    if (flock($file, LOCK_EX)) { // exklusive Sperre
      ftruncate($file, 0); // kürze Datei
      fwrite($file, serialize($content));
      fflush($file); // leere Ausgabepuffer bevor die Sperre frei gegeben wird
      flock($file, LOCK_UN); // Gib Sperre frei
    } else {
      print_r("Konnte Sperre nicht erhalten!");
    }

    fclose($file);
  }

  public function deserialize($fileName) {
    $array = array();
    $file = fopen($this->defaultFilePath . $fileName, "r");

    if (flock($file, LOCK_SH)) { // geteilte Sperre
      $fileSize = filesize($this->defaultFilePath . $fileName);

      if ($fileSize > 0) {
        $array = unserialize(fread($file, $fileSize));
      } else {
        print_r("Kein Dateiinhalt zum Deserialisieren vorhanden.");
      }
      fflush($file); // leere Ausgabepuffer bevor die Sperre frei gegeben wird
      flock($file, LOCK_UN); // Gib Sperre frei
    } else {
      print_r("Konnte Sperre nicht erhalten!");
    }

    fclose($file);

    return $array;
  }

  public function emptyFile($fileName) {
    $file = fopen($this->defaultFilePath . $fileName, "r+");

    if (flock($file, LOCK_EX)) { // exklusive Sperre
      ftruncate($file, 0); // kürze Datei
      fflush($file); // leere Ausgabepuffer bevor die Sperre frei gegeben wird
      flock($file, LOCK_UN); // Gib Sperre frei
    } else {
      print_r("Konnte Sperre nicht erhalten!");
    }

    fclose($file);
  }
}