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
  $retorno = $cliente->login();
}


/* Config */

if($_GET['tipo']=="config" && $_GET['id']=="perfil"){
  $cliente = new Cliente();
  $input=converterInput();
  $token=new Token();
  $token=$token->validar();
  if($token->valido){
    $cliente->id=$token->id;
    if(count($input)>0){//foi POST
      $cliente->nome=$input->nome;
      $cliente->senha=$input->senha;
      $cliente->email=$input->email;
      $retorno = $cliente->salvar();
    }else{//foi GET
      $retorno = $cliente->ler();
    }
  }else{
    $retorno = Erro("Token invalido.",403,"Forbidden");
  }
}

/* Limpar possiveis respostas de uso interno */
unset($retorno->existe);
unset($retorno->senha);

/* Responder ao cliente */
responder($retorno);
exit; // fim

?>
