<?php

class FileHandler {

  public function serialize($fileName, $content) {
    $file = fopen($fileName, "r+");

    if (flock($file, LOCK_EX)) { // exklusive Sperre
      ftruncate($file, 0); // Datei kürzen
      fwrite($file, base64_encode(serialize($content)));
      fflush($file); // leere Ausgabepuffer bevor die Sperre frei gegeben wird
      flock($file, LOCK_UN);
    }

    fclose($file);
  }

  public function deserialize($fileName) {
    $array = array();

    $file = fopen($fileName, "r");

    if (flock($file, LOCK_SH)) { // geteilte Sperre
      $fileSize = filesize($fileName);

      if ($fileSize > 0) {
        $array = unserialize(base64_decode(fread($file, $fileSize)));
      }

      fflush($file); // leere Ausgabepuffer bevor die Sperre frei gegeben wird
      flock($file, LOCK_UN);
    }

    fclose($file);

    return $array;
  }

  public function emptyFile($fileName) {
    $file = fopen($fileName, "r+");

    if (flock($file, LOCK_EX)) { // exklusive Sperre
      ftruncate($file, 0); // Datei kürzen
      fflush($file); // leere Ausgabepuffer bevor die Sperre frei gegeben wird
      flock($file, LOCK_UN);
    }

    fclose($file);
  }
}