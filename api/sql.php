<?php

class sql {
  private $server = "127.0.0.1";
  private $port = "3306";
  private $database = "chatapi";
  private $username = "chatapi";
  private $password = "ipatahc";

  private $conn;

  function conectar(){
    $this->conn=mysqli($server, $username, $password, $database, $port);
  }

  function setServer($ip){
    $this->server=$ip;
  }
  function setPort($port){
    $this->port=$port;
  }
  function setDatabase($db){
    $this->database=$db;
  }
  function setUsername($user){
    $this->username=$user;
  }
  function setPassword($pass){
    $this->password=$pass;
  }

  function pegaCliente($id){
    
  /* adicionar no objeto retornado:
     $obj->existe=true;  //cliente existe
     $obj->existe=false; //cliente nao existe
  */
  }
  function salvaCliente($cliente){

  }

  function geraToken($id){

  }

  function validaToken($token){


  }
  

}







?>
