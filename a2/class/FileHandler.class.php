<?php

class FileHandler {

  public function serialize($fileName, $content) {
    $file = fopen($fileName, "r+");

    if (flock($file, LOCK_EX)) { // exklusive Sperre
      ftruncate($file, 0); // kürze Datei
      fwrite($file, serialize($content));
      fflush($file); // leere Ausgabepuffer bevor die Sperre frei gegeben wird
      flock($file, LOCK_UN); // Gib Sperre frei
    }

    fclose($file);
  }

  public function deserialize($fileName) {
    $array = array();

    $file = fopen($fileName, "r");

    if (flock($file, LOCK_SH)) { // geteilte Sperre
      $fileSize = filesize($fileName);

      if ($fileSize > 0) {
        $array = unserialize(fread($file, $fileSize));
      }

      fflush($file); // leere Ausgabepuffer bevor die Sperre frei gegeben wird
      flock($file, LOCK_UN); // Gib Sperre frei
    }

    fclose($file);

    return $array;
  }

  public function emptyFile($fileName) {
    $file = fopen($fileName, "r+");

    if (flock($file, LOCK_EX)) { // exklusive Sperre
      ftruncate($file, 0); // kürze Datei
      fflush($file); // leere Ausgabepuffer bevor die Sperre frei gegeben wird
      flock($file, LOCK_UN); // Gib Sperre frei
    }

    fclose($file);
  }
}