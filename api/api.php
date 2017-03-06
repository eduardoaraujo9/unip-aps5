<?php

/******************************************************************************
 *                                                                            *
 * API de Chat, desenvolvida como APS do 5.o semestre no curso de Ciencias da *
 *              Computacao na UNIP em Jan/2017.                               *
 *                                                                            *
 * Autor: Eduardo Araujo                                                      *
 *                                                                            *
 ******************************************************************************/

include "classes.php";
include "functions.php";

/* Inicio */

/* if(count($_GET)==0){ echo "acessou / raiz"; }
// poderia redirecionar para a doc
*/

/* Login */

if($_GET['tipo']=="login"){
  $cliente = new Cliente();
  $input=converterInput();
  $cliente->email=$input->email;
  $cliente->senha=$input->senha;
  $retorno = $cliente->validar();
}


/* Config */

if($_GET['tipo']=="config" && $_GET['id']=="perfil"){
  $cliente = new Cliente();
  $input=converterInput();
  $cliente->nome=$input->nome;
  $cliente->senha=$input->senha;
  $cliente->email=$input->email;
  $token=new Token();
  $token=$token->validar();
  var_dump($token);exit;
  if($token->valid&&$token->id==$cliente->id){
    $cliente->id="5";
    $retorno = $cliente->salvar();
  }else{
    $retorno = Erro("Token invalido.",403,"Forbidden");
  }
}


/* Responder ao cliente */
responder($retorno);
exit; // fim

?>
