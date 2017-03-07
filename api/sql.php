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

  function lerClienteEmail($email){
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

  function lerClienteId($id){
    $this->conectar();
    if($this->conn->connect_errno){
      $obj->erro=true;
      $obj->error->msg="Erro de conexao ao banco de dados.";
      $obj->error->code=503;
      $obj->error->short="Service Unavailable";
    }else{
      $result = $this->conn->query("SELECT * FROM clientes WHERE id = '" . $id . "';");
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
        $query=array();
        if(strlen($cliente->nome)>0){array_push($query,"`nome`='" . $cliente->nome . "'");}
        if(strlen($cliente->email)>0){array_push($query,"`email`='" . $cliente->email . "'");}
        if(strlen($cliente->senha)>0){array_push($query,"`senha`='" . $cliente->senha . "'");}
        if(strlen($cliente->lastupdate)>0){array_push($query,"`lastupdate`='" . $cliente->lastupdate . "'");}
        if(count($query)>0){
          $result = $this->conn->query("UPDATE clientes SET " . implode($query,","). " WHERE `id`='" . $cliente->id . "';");
        }else{
          $obj->erro=true;
          $obj->error->msg="Requisicao errada, faltam os parametros.";
          $obj->error->code=300;
          $obj->error->short="Bad Request";
        }
      }else if(strlen($cliente->email)==0||strlen($cliente->senha)==0){
        $obj->erro=true;
        $obj->error->msg="Requisicao errada, faltam os parametros.";
        $obj->error->code=300;
        $obj->error->short="Bad Request";
      }else{
        $result = $this->conn->query("INSERT INTO clientes (`nome`,`email`,`senha`) VALUES ('" . $cliente->nome . "','" . $cliente->email . "','" . $cliente->senha . "');");
      }
      if(!$result&&!$obj->erro){
        $obj->erro=true;
        $obj->error->msg="Erro interno no banco de dados.";
        $obj->error->code=500;
        $obj->error->short="Internal Server Error";
      }
    }
    if($obj->erro){return $obj;}
    if(strlen($cliente->email)>0){
      return $this->lerClienteEmail($cliente->email); //retorna o cliente salvo ou atualizado (com id criado ou erro)
    }else{
      return $this->lerClienteId($cliente->id);
    }
  }

  function salvarToken($token){
    $this->conectar();
    if($this->conn->connect_errno){
      $obj->erro=true;
      $obj->error->msg="Erro de conexao ao banco de dados.";
      $obj->error->code=503;
      $obj->error->short="Service Unavailable";
    }else{
      $result = $this->conn->query("SELECT * FROM tokens WHERE id = '" . $token->id . "';");
      if(!$result){
        $obj->erro=true;
        $obj->error->msg="Erro interno no banco de dados.";
        $obj->error->code=500;
        $obj->error->short="Internal Server Error";
      }else{
        if($result->num_rows=="0"){
          $result = $this->conn->query("INSERT INTO tokens (`id`,`token`,`validade`) VALUES ('" . $token->id . "','" . $token->token . "','" . $token->validade . "');");
        }else{
          $obj=$result->fetch_object();
          $query=array();
          if($token->token!=$obj->token){array_push($query,"`token`='" . $token->token . "'");}
          array_push($query,"`validade`='" . $token->validade . "'");
          $result = $this->conn->query("UPDATE tokens SET " . implode($query,","). " WHERE `id`='" . $token->id . "';");
        }
        if(!$result){
          $obj->erro=true;
          $obj->error->msg="Erro interno no banco de dados.";
          $obj->error->code=500;
          $obj->error->short="Internal Server Error";
        }else{
          return $token;
        }
      }
    }
    return $obj;
  }

  function lerTokenId($id){
    $this->conectar();
    if($this->conn->connect_errno){
      $obj->erro=true;
      $obj->error->msg="Erro de conexao ao banco de dados.";
      $obj->error->code=503;
      $obj->error->short="Service Unavailable";
    }else{
      $result = $this->conn->query("SELECT * FROM tokens WHERE `id`='" . $id . "';");
      if(!$result){
        $obj->erro=true;
        $obj->error->msg="Erro interno no banco de dados.";
        $obj->error->code=500;
        $obj->error->short="Internal Server Error";
      }else{
        if($result->num_rows=="0"){
          $obj->id=0;
          $obj->token="0000";
          $obj->validade=new DateTime();
          $obj->validade->sub('PT1S');
        }else{
          $obj=$result->fetch_object();
        }
      }
    }
    return $obj;
  }

  function lerTokenHash($token){
    $this->conectar();
    if($this->conn->connect_errno){
      $obj->erro=true;
      $obj->error->msg="Erro de conexao ao banco de dados.";
      $obj->error->code=503;
      $obj->error->short="Service Unavailable";
    }else{
      $result = $this->conn->query("SELECT * FROM tokens WHERE `token`='" . $token . "';");
      if(!$result){
        $obj->erro=true;
        $obj->error->msg="Erro interno no banco de dados.";
        $obj->error->code=500;
        $obj->error->short="Internal Server Error";
      }else{
        if($result->num_rows=="0"){
          $obj->id=0;
          $obj->token="0000";
          $obj->validade=new DateTime();
          $obj->validade->sub('PT1S');
        }else{
          $obj=$result->fetch_object();
        }
      }
    }
    return $obj;
  }

  function salvarChat($chat){
    $this->conectar();
    if($this->conn->connect_errno){
      $obj->erro=true;
      $obj->error->msg="Erro de conexao ao banco de dados.";
      $obj->error->code=503;
      $obj->error->short="Service Unavailable";
    }else{
      if(strlen($chat->cliente)>0&&strlen($chat->dados)>0){
        $result = $this->conn->query("INSERT INTO chat (`cliente`,`hora`,`tipo`,`dados`) VALUES ('" . $chat->cliente . "','" . $chat->hora . "','" . $chat->tipo . "','" . $chat->dados . "');");
      }else{
          $obj->erro=true;
          $obj->error->msg="Requisicao errada, faltam os parametros.";
          $obj->error->code=300;
          $obj->error->short="Bad Request";
      }
      if(!$result&&!$obj->erro){
        $obj->erro=true;
        $obj->error->msg="Erro interno no banco de dados.";
        $obj->error->code=500;
        $obj->error->short="Internal Server Error";
      }
    }
    if($obj->erro){return $obj;}
    $id=$this->pegarChatId($chat); //retorna o chat com id (ou erro)
    $obj->lastupdate=$id->id;
    return $obj;
  }

  function pegarChatId($chat){
    $this->conectar();
    if($this->conn->connect_errno){
      $obj->erro=true;
      $obj->error->msg="Erro de conexao ao banco de dados.";
      $obj->error->code=503;
      $obj->error->short="Service Unavailable";
    }else{
      $result = $this->conn->query("SELECT `id` FROM chat WHERE (`cliente`='" . $chat->cliente . "' AND `hora`='" . $chat->hora . "' AND `tipo`='" . $chat->tipo . "' AND `dados`='" . $chat->dados . "');");
      if(!$result&&!$obj->erro){
        $obj->erro=true;
        $obj->error->msg="Erro interno no banco de dados.";
        $obj->error->code=500;
        $obj->error->short="Internal Server Error";
      }
    }
    if($obj->erro){return $obj;}
    return $result->fetch_object(); //retorna o chat com id (ou erro)
  }




}
?>
