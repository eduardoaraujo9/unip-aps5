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
