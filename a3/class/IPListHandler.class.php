<?php

require_once('FileHandler.class.php');

class IPListHandler {

  private $fileHandler;

  function __construct() {
    $this->fileHandler = new FileHandler();
  }

  public function update($ipList) {
    $deserialized = $this->fileHandler->deserialize('persistence/iplist.txt');
    $deserialized['all'] = $ipList;
    $this->fileHandler->serialize($this->fileName, $deserialized);
  }

  public function getList() {
    $ipList = $this->fileHandler->deserialize('persistence/iplist.txt');

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
      return next($ipList[$myIP]);
    } else {
      return "";
    }
  }

  public function setMyIP($ip, $name) {
    $ipList = $this->fileHandler->deserialize('persistence/iplist.txt');
    $ipList['me']['IP'] = $ip;
    $ipList['me']['name'] = $name;
    $this->fileHandler->serialize('persistence/iplist.txt');
  }

  public function getMyIP() {
    $ipList = $this->fileHandler->deserialize('persistence/iplist.txt');

    if (array_key_exists('me', $ipList)) {
      return $ipList['me']['IP'];
    } else {
      return "";
    }
  }

  public function getMyName() {
    $ipList = $this->fileHandler->deserialize('persistence/iplist.txt');
    return $ipList['me']['name'];
  }
}