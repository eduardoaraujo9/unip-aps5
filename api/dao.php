<?php

/* Data Access Object (DAO) */

include "sql.php";

class DAO {
  private $dados;

  function __construct() {
    $this->dados = new sql();
    $this->dados->conectar;
  }

  function salvarCliente($cliente) {
    return $this->dados->salvaCliente($cliente);
  }
  
  function lerCliente($cliente) {
    return $this->dados->pegaCliente($cliente->email);
  }

  function salvarChat($obj) {

  }

  function lerChat($obj) {

  }

  function salvarConfig($obj) {

  }

  function lerConfig($obj) {

  }

  function geraToken($id){
    echo "gerar token:".$id;exit;
  }
  function validarToken($obj) {

  }

}

?>
