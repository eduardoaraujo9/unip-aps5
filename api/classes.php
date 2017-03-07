<?php

include "dao.php";

class Chat {
  public $id;
  public $cliente;
  public $hora;
  public $tipo;
  public $dados;

  private $DAO;
  function __construct(){
    $this->DAO = new DAO();
  }
  
  function post(){
    $this->hora=new DateTime();
    $this->hora=$this->hora->format('Y-m-d H:i:s');
    if(strlen($this->tipo)==0){$this->tipo=0;}
    return $this->DAO->salvarChat($this);
  }

  function ler(){
    return $this->id;
  }
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
  
  function login(){
    if(strlen($this->email)>0){
      $cliente = $this->DAO->lerClienteEmail($this->email);
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
  
  function ler(){
    if(strlen($this->email)>0){return $this->DAO->lerClienteEmail($this->email);}
    elseif(strlen($this->id)>0){return $this->DAO->lerClienteId($this->id);}
    else{return Erro("Erro interno",500,"Internal Server Error");}
  }

  function salvar(){
    return $this->DAO->salvarCliente($this);
  }

  function atualizar(){
    $obj=$this->DAO->lerClienteId($this->id);
    $obj->lastupdate=$this->lastupdate;
    return $this->DAO->salvarCliente($obj);
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
    $header = getallheaders();
    if(isset($header['access_token'])){$this->token=$header['access_token'];}
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
    if(strlen($this->id)==0&&strlen($this->token)==0){
      return Erro("Token invalido.",403,"Forbidden");
    }
    if(strlen($this->id)==0&&strlen($this->token)>0){
      $obj=$this->DAO->lerTokenHash($this->token);
      $this->id=$obj->id;
    }else{
      $obj=$this->DAO->lerTokenId($this->id);
    }
    $this->valido=false;
    if($obj->token==$this->token){
      $date=new DateTime($obj->validade);
      if($date->diff(new DateTime())->format('%R')=="-"){
        $this->valido=true;
      }
    }
    if(!$this->valido){
      $return = Erro("Token invalido.",403,"Forbidden");
      $return->valido=false;
      return $return;
    }
    $this->validade=$obj->validade;
    return $this;
  }

  function atualizar(){
    $this->validade=new DateTime();
    $this->validade=$this->validade->add(new DateInterval('PT30M'));
    $this->validade=$this->validade->format('Y-m-d H:i:s');
    if(strlen($this->id)==0&&strlen($this->token)==0){
      return Erro("Token invalido.",403,"Forbidden");
    }
    if(strlen($this->id)>0&&strlen($this->token)==0){
      $obj=$this->DAO->lerTokenId($this->id);
      $this->token=$obj->token;
    }else{
      $obj=$this->DAO->lerTokenHash($this->token);
      $this->id=$obj->id;
    }
    $access = $this->DAO->salvarToken($this);
    return $access->token;
  }


}

?>
