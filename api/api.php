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
  $token=new Token();
  $token=$token->validar();
  if($token->valido){
    $cliente = new Cliente();
    $cliente->id=$token->id;
    $input=converterInput();
    if(count($input)>0){//foi POST
      $cliente->nome=$input->nome;
      $cliente->senha=$input->senha;
      $cliente->email=$input->email;
      $retorno = $cliente->salvar();
    }else{//foi GET
      $retorno = $cliente->ler();
    }
    $token->atualizar();
  }else{
    $retorno = Erro("Token invalido.",403,"Forbidden");
  }
}


/* Mensagens */
if($_GET['tipo']=="msg"){
  $token=new Token();
  $token=$token->validar();
  if($token->valido){
    $cliente = new Cliente();
    $cliente->id=$token->id;
    $input=converterInput();
    $chat = new Chat();
    if(count($input)>0&&strlen($input->lastupdate)==0){//foi POST
      $chat->cliente=$cliente->id;
      $chat->tipo=$input->tipo;
      $chat->dados=$input->dados; 
      $retorno = $chat->post();
      $cliente->lastupdate=$retorno->lastupdate;
      $cliente->atualizar();
    }else{//foi GET
      if(strlen($input->lastupdate)>0){
        $chat->id=$input->lastupdate;
      }else{
        $obj=$cliente->ler();
        $chat->id=$obj->lastupdate;
      }
      $retorno = $chat->ler();
      $lastupdate=$retorno[count($retorno)-1]->lastupdate;
      $cliente->lastupdate=$lastupdate;
      $cliente->atualizar();
    }
    $token->atualizar();
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
