<?php

/******************************************************************************
 *                                                                            *
 * API de Chat, desenvolvida como APS do 5.o semestre no curso de Ciencias da *
 *              Computacao na UNIP em Jan/2017.                               *
 *                                                                            *
 * Autor: Eduardo Araujo                                                      *
 *                                                                            *
 ******************************************************************************/

include "dao.php";
include "classes.php";
include "functions.php";


/* inicio */
/* if(count($_GET)==0){ echo "acessou / raiz"; }

/*
echo count($_GET);
echo " tipo:" . $_GET['tipo'];
echo " id:" . $_GET['id'];
*/

$DAO = new DAO();

/* Login */

if(count($_POST)>0 && $_GET['tipo']=="login"){
  $cliente = new Cliente();
  $cliente->email=$_POST['email'];
  $cliente->senha=$_POST['senha'];
  $retorno = $cliente->validar();
}

// retorna os dados de acordo com o request do cliente
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
