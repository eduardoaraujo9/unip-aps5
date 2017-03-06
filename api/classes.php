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
          $token=new Token();
          $token->id=$cliente->id;
          $retorno->access_token=$token->gerar();
        }else{
          $retorno=Erro("Erro no login: email ou senha errados.",403,"Forbidden");
        }
      }else{
        $cliente=$this->salvar();
        if($cliente->erro){
          $retorno=Erro($cliente->error->msg,$cliente->error->code,$cliente->error->short);
        }else{
          $token=new Token();
          $token->id=$cliente->id;
          $retorno->access_token=$token->gerar();
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
/*
        if($cliente->erro){
          $retorno=Erro($cliente->error->msg,$cliente->error->code,$cliente->error->short);
        }else{
          $retorno->access_token=$this->gerarToken($cliente->id);
        }
*/
  }

  function atualizou(){
    $this->DAO->atualizarCliente($this);
//esse eh o last update
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

  private $DAO;
  function __construct() {
    $this->DAO = new DAO();
  }

  function gerar(){
    $this->token=dechex($this->id) . dechex(rand(536870911,4294967295));
    $this->validade=new DateTime();
    $this->validade->add(new DateInterval('PT30M'));
    $this->validade=$this->validade->format('Y-m-d H:i:s');
    $access = $this->DAO->salvarToken($this);
    return $access->token;
  }

  function validar(){
    if(strlen($this->id)>0){ return Erro("Nao foi possivel validar o id token.",501,"Not Implemented");}//busca por id
    else if(strlen($this->token)>0){ return Erro("Nao foi possivel validar o token.",501,"Not Implemented");}//busca por token
    else{
      return Erro("Token invalido.",403,"Forbidden");
    }
  }

  function atualizar(){
    $this->id=$id;
    $this->token=dechex($id) . dechex(rand(536870911,4294967295));
    $this->validade=new DateTime();
    $this->validade->add(new DateInterval('PT30M'));
    $this->validade=$this->validade->format('Y-m-d H:i:s');
    $access = $this->DAO->salvarToken($this);
  }

  function validarToken($token){

/*
    $date=new DateTime();
    $date->add(new DateInterval('PT30M'));
    $time=explode(":",$date->diff(new DateTime())->format('%i:%s'));
    $time[0]*=60;
    $time[0]+=$time[1];
    //$date->format('Y-m-d H:i:s')
*/
    $obj->id='5';
    return $this->DAO->lerToken($obj);
//$retorno=Erro("Erro no login: email ou senha errados.",403,"Forbidden");

  }


}

?>
