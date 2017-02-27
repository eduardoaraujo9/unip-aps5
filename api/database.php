<?php

class sql {
  private $server = "127.0.0.1";
  private $port = "3306";
  private $database = "chatapi";
  private $username = "chatapi";
  private $password = "ipatahc";

  private $conn;

  function conectar(){
    $conn = mysqli($server, $username, $password, $database, $port);
  }

  function setServer($ip){
    $this->server=$ip;
  }
  function getServer(){
    return $this->server;
  }


}







?>
