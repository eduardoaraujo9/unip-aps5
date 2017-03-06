<?php

/* Data Access Object (DAO) */

include "sql.php";

class DAO {
  private $dados;

  function __construct(){
    $this->dados = new sql();
    $this->dados->conectar;
  }

  function salvarCliente($cliente){
    return $this->dados->salvaCliente($cliente);
  }
  
  function lerClienteEmail($email){
    return $this->dados->lerClienteEmail($email);
  }
  function lerClienteId($id){
    return $this->dados->lerClienteId($id);
  }

  function salvarChat($obj) {

  }

  function lerChat($obj) {

  }

  function salvarConfig($obj) {

  }

  function lerConfig($obj) {

  }

  function salvarToken($token){
    return $this->dados->salvarToken($token);
  }

  function lerTokenId($id) {
    return $this->dados->lerTokenId($id);
  }

  function lerTokenHash($token){
    return $this->dados->lerTokenHash($token);
  }

}

?>
