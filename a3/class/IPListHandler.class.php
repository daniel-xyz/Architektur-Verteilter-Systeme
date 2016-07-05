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

    if (!empty($myIP)) {
      $indexOfMyIP = array_search($myIP, array_keys($ipList));

      error_log("Index meiner IP: " . $indexOfMyIP);

      if (!empty($ipList[$indexOfMyIP + 1])) {
        return $ipList[$indexOfMyIP + 1];
      }
    } else {
      return "";
    }
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
}