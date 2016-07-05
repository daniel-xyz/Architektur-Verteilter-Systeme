<?php

require_once('FileHandler.class.php');

class IPListHandler {

  private $fileHandler;
  private $fileName = 'persistence/iplist.txt';

  function __construct() {
    $this->fileHandler = new FileHandler();
  }

  public function update($ipList) {
    $ipList = $this->fileHandler->deserialize($this->fileName);
    $ipList['all'] = $ipList;
    $this->fileHandler->serialize($this->fileName, $ipList);
  }

  public function getList() {
    $ipList = $this->fileHandler->deserialize($this->fileName);
    return $ipList['all'];
  }

  public function getMyNextNeighborsIP() {
    $ipList = $this->getList();
    $myIP = $this->getMyIP();
    return next($ipList[$myIP]);
  }

  public function setMyIP($ip, $name) {
    $ipList = $this->fileHandler->deserialize($this->fileName);
    $ipList['me']['IP'] = $ip;
    $ipList['me']['name'] = $name;
    $this->fileHandler->serialize($this->fileName, $ipList);
  }

  public function getMyIP() {
    $ipList = $this->fileHandler->deserialize($this->fileName);
    return $ipList['me']['IP'];
  }

  public function getMyName() {
    $ipList = $this->fileHandler->deserialize($this->fileName);
    return $ipList['me']['name'];
  }
}