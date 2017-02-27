<?php

/* Data Access Object (DAO) */

include "database.php";

class DAO {
  private $db = new sql();

  function __construct() {
    $db->conectar;
  }

  function salvarCliente($obj) {

  }
  
  function lerCliente($obj) {

  }

  function salvarChat($obj) {

  }

  function lerChat($obj) {

  }

  function salvarConfig($obj) {

  }

  function lerConfig($obj) {

  }

}

?>
