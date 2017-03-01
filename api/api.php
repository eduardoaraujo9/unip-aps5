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

/*
echo count($_GET);
echo " tipo:" . $_GET['tipo'];
echo " id:" . $_GET['id'];
*/

//$DAO = new DAO();

/* Login */

if($_GET['tipo']=="login" && count($_POST)>0){
  $cliente = new Cliente();
  $cliente->email = $_POST['email'];
  $cliente->senha = $_POST['senha'];
  $retorno = $cliente->validar();
}


/* Retorna os dados de acordo com o request do cliente */
if(count($retorno)==0){$retorno=Erro();}
switch (getContentType()){
	case 'application/xml':
		returnXML(); break;
	case 'text/html':
		returnHTML(); break;
	case 'text/plain':
		returnText(); break;
	default: returnJson();
}
exit; // fim

?>
