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
      $cliente = $this->DAO->lerCliente($this);
      if($cliente->erro){
        $retorno=Erro($cliente->error->msg,$cliente->error->code,$cliente->error->short);
      }else if($cliente->existe){
        if($cliente->senha==$this->senha){
          $retorno->access_token=$this->DAO->geraToken($cliente->id);
        }else{
          $retorno=Erro("Erro no login: email ou senha errados.",403,"Forbidden");
        }
      }else{
        $cliente=$this->salvar();
        if($cliente->erro){
          $retorno=Erro($cliente->error->msg,$cliente->error->code,$cliente->error->short);
        }else{
          $retorno->access_token=$this->DAO->geraToken($cliente->id);
        }
      }
    }else{
      $retorno=Erro("Erro no login: email ou senha errados.",403,"Forbidden");
    }
    return $retorno;
  }

  function salvar(){
    //if sem ID salva
    //else valida token
    return $this->DAO->salvarCliente($this);
  }

  function atualizou(){
    $this->DAO->atualizarCliente($this);
  }
  function validarToken(){

  }
  function atualizarToken(){

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
