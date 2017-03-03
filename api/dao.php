<?php

/* Data Access Object (DAO) */

include "sql.php";

class DAO {
  private $dados;

  function __construct() {
    $this->dados = new sql();
    $this->dados->conectar;
  }

  function loginCliente($cliente) {
/* workflow:
$dadosCliente=$this->dados->pegaCliente($cliente->id);
if(!$dadosCliente->existe){$this->dados->salvaCliente($cliente);}
if($cliente->senha==$dadosCliente->senha){$this->dados->geraToken($cliente->id);}


*/
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
