<?php

/* Data Access Object (DAO) */

include "sql.php";

class DAO {
  private $db;

  function __construct() {
    $this->db = new sql();
    $this->db->conectar;
  }

  function loginCliente($cliente) {
    if($cliente->senha=="123"){
      $retorno->access_token="ok:" . $cliente->email;
    }else{
      $retorno=Erro("Erro no login: email ou senha errados.",403,"Forbidden");
    }
    return $retorno;
  }

  function salvarCliente($obj) {

  }
  
  function lerCliente($obj) {

  }

  function salvarChat($obj) {

  }

  function lerChat($obj) {

  }

  function salvarConfig($obj) {

  }

  function lerConfig($obj) {

  }

  function validarToken($obj) {

  }

}

?>
