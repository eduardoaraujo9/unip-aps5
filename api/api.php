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

echo "tipo:" . $_GET['tipo'];
echo " id:" . $_GET['id'];

$DAO = new DAO();

//echo "db: " . $base->getServer();


?>
