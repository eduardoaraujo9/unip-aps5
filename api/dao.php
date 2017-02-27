<?php

/* Data Access Object (DAO) */

include "sql.php";

class DAO {
  private $db;

  function __construct() {
    $this->db = new sql();
    $this->db->conectar;
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
