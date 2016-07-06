<?php

require_once('FileHandler.class.php');

class IPListHandler {

  private $fileHandler;
  private $fileName = 'iplist.txt';

  function __construct() {
    $this->fileHandler = new FileHandler();
  }

  public function update($ipList) {
    $deserialized = $this->fileHandler->deserialize($this->fileName);
    $deserialized['all'] = $ipList;
    $this->fileHandler->serialize($this->fileName, $deserialized);
  }

  public function getList() {
    $ipList = $this->fileHandler->deserialize($this->fileName);

    if (array_key_exists('all', $ipList)) {
      return $ipList['all'];
    } else {
      return array();
    }
  }

  public function getMyNextNeighborsIP() {
    $ipList = $this->getList();
    $myIP = $this->getMyIP();
    $keys = array_keys($ipList);

    $indexOfMyIP = array_search($myIP, array_keys($ipList));
    error_log("Index meiner IP: " . $indexOfMyIP); // TODO Server wusste sie nicht

    if ($indexOfMyIP < (count($ipList) - 1)) {
      $neighbor = $ipList[$keys[$indexOfMyIP + 1]];
      $next = $neighbor['ip'];
      error_log("Mein nächster Nachbar: " . $neighbor['ip']);
    } else {
      $next = $ipList[$keys[0]]['ip'];
    }

    return $next;
  }

  public function getMyNextNeighborsIPFromTemporaryList($ipList, $myIP) {
    $keys = array_keys($ipList);
    $indexOfMyIP = array_search($myIP, array_keys($ipList));
    error_log("yourNeighbor.php: Index meiner IP in der neuen IP-Liste: " . $indexOfMyIP);

    if ($indexOfMyIP < (count($ipList) - 1)) {
      $neighbor = $ipList[$keys[$indexOfMyIP + 1]];
      $next = $neighbor['ip'];
      error_log("yourNeighbor.php: Mein nächster Nachbar: " . $neighbor['ip']);
    } else {
      $next = $ipList[$keys[0]]['ip'];
    }

    return $next;
  }

  public function setMyIP($ip, $name) {
    $ipList = $this->fileHandler->deserialize($this->fileName);
    $ipList['me']['ip'] = $ip;
    $ipList['me']['name'] = $name;
    $this->fileHandler->serialize($this->fileName, $ipList);
  }

  public function getMyIP() {
    $ipList = $this->fileHandler->deserialize($this->fileName);

    if (array_key_exists('me', $ipList)) {
      return $ipList['me']['ip'];
    } else {
      return "";
    }
  }

  public function getMyName() {
    $ipList = $this->fileHandler->deserialize($this->fileName);
    return $ipList['me']['name'];
  }

  public function getNameForIP($ip) {
    $ipList = $this->fileHandler->deserialize($this->fileName);
    return $ipList['all'][$ip]['name'];
  }
}