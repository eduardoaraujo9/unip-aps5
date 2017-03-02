<?php

include "dao.php";

class Chat {
  public $id;
  public $cliente;
  public $hora;
  public $tipo;
  public $dados;
}

class Cliente {
  public $id;
  public $nome;
  public $email;
  public $senha;
  public $lastupdate;

  private $DAO;
  function __construct() {
    $this->DAO = new DAO();
  }
  
  function validar(){
    if(strlen($this->email)>0){
      $retorno = $this->DAO->loginCliente($this);
    }else{
      $retorno=Erro("Erro no login: email ou senha errados.",403,"Forbidden");
    }
    return $retorno;
  }

  function salvar(){
    $this->DAO->salvarCliente($this);
  }

  function atualizou(){
    $this->DAO->atualizarCliente($this);
  }

}

class Config {
  public $id;
  public $tema;
}

class Token {
  public $id;
  public $token;
  public $validade;
}

?>
