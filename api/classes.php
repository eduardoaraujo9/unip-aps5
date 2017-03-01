<?php

/* Prototipo das classes */

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

  function validar(){
    $retorno->access_token = "ok";
    $retorno->message = "acesso concedido";
    return $retorno;
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
