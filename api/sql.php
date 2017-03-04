<?php

class sql {
  private $server = "127.0.0.1";
  private $port = "3306";
  private $database = "chatapi";
  private $username = "chatapi";
  private $password = "ipatahc";

  private $conn;

  function conectar(){
    $this->conn=new mysqli($this->server, $this->username, $this->password, $this->database, $this->port);
  }

  function setServer($ip){
    $this->server=$ip;
  }
  function setPort($port){
    $this->port=$port;
  }
  function setDatabase($db){
    $this->database=$db;
  }
  function setUsername($user){
    $this->username=$user;
  }
  function setPassword($pass){
    $this->password=$pass;
  }

  function pegaCliente($email){
    $this->conectar();
    if($this->conn->connect_errno){
      $obj->erro=true;
      $obj->error->msg="Erro de conexao ao banco de dados.";
      $obj->error->code=503;
      $obj->error->short="Service Unavailable";
    }else{
      $result = $this->conn->query("SELECT * FROM clientes WHERE email = '" . $email . "';");
      if(!$result){
        $obj->erro=true;
        $obj->error->msg="Erro interno no banco de dados.";
        $obj->error->code=500;
        $obj->error->short="Internal Server Error";
      }else{
        if($result->num_rows=="0"){$obj->existe=false;}
        else{
          $obj=$result->fetch_object();
          $obj->existe=true;
        }
      }
    }
    return $obj;
  }

  function salvaCliente($cliente){
    $this->conectar();
    if($this->conn->connect_errno){
      $obj->erro=true;
      $obj->error->msg="Erro de conexao ao banco de dados.";
      $obj->error->code=503;
      $obj->error->short="Service Unavailable";
    }else{
      if(strlen($cliente->id)>0){
// compoe query soh com os elementos existentes (push_array+implode",")
        $result = $this->conn->query("UPDATE clientes SET `nome`='" . $cliente->nome . "',`email`='" . $cliente->email . "',`senha`='" . $cliente->senha . "' WHERE `id`='" . $cliente->id . "';");
      }else{
        $result = $this->conn->query("INSERT INTO clientes (`nome`,`email`,`senha`) VALUES ('" . $cliente->nome . "','" . $cliente->email . "','" . $cliente->senha . "');");
      }
      if(!$result){
        $obj->erro=true;
        $obj->error->msg="Erro interno no banco de dados.";
        $obj->error->code=500;
        $obj->error->short="Internal Server Error";
      }
    }
    return $this->pegaCliente($cliente->email); //retorna o cliente salvo ou atualizado (com id ou erro)

  }

  function geraToken($id){

  }

  function validaToken($token){


  }
  

}







?>
