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
  if($token->valido){
    $cliente = new Cliente($token->id);
    $input=converterInput();
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


/* Mensagens */

if($_GET['tipo']=="msg"){
  $token=new Token();
  if($token->valido){
    $cliente = new Cliente($token->id);
    $input=converterInput();
    $chat = new Chat();
    if(count($input)>0&&strlen($input->lastupdate)==0){//foi POST
      $chat->cliente=$cliente->id;
      $chat->tipo=$input->tipo;
      $chat->dados=$input->dados; 
      $retorno = $chat->post();
	  $retorno->hora=$chat->hora;
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
  }else{
    $retorno = Erro("Token invalido.",403,"Forbidden");
  }
}


/* Uploads */

if($_GET['tipo']=="envio"){
	$token=new Token();
	if($token->valido){

		try {
			$uploads_dir = getcwd() . '/../files';
			mkdir($uploads_dir);
			foreach ($_FILES["file"]["error"] as $key => $error) {
				if ($error == UPLOAD_ERR_OK) {
					$tmp_name = $_FILES["file"]["tmp_name"][$key];
					$name = basename($_FILES["file"]["name"][$key]);
					// ajustar nome do arquivo
					if(!move_uploaded_file($tmp_name, "$uploads_dir/$name")){
						throw new RuntimeException('Erro no upload.');
					}
				}
			}

			//$hash = "";
			
			$cliente = new Cliente($token->id);
			$chat = new Chat();

			$chat->cliente=$cliente->id;
			if(preg_match("/(jpe?g|gif|png)/i", substr($name,-3))){
				$chat->tipo=2;
			}else{
				$chat->tipo=3;
			}
			//$chat->dados="http://unip.now.im/files/" . $hash . "/" . $name;
			$chat->dados="http://unip.now.im/files/" . $name;
			$retorno = $chat->post();
			$cliente->lastupdate=$retorno->lastupdate;
			$cliente->atualizar();
			$retorno->tipo=$chat->tipo;
			$retorno->dados=$chat->dados;
			$retorno->hora=$chat->hora;

		} catch (RuntimeException $e) {
			$retorno = Erro($e->getMessage());

		}

	}else{
		$retorno = Erro("Token invalido.",403,"Forbidden");
	}
}


/* Receber */

if($_GET['tipo']=="receber" && strlen($id)>0 && strlen($parm)>0){
	$parm = explode("/", $parm);
	if(isset($parm[1])){
		$parm=$parm[1];
		$retorno= [ 
			'hash' => $id,
			'file' => $parm
		];
	}else{
		$retorno=Erro("file not found");
	}
}


/* Limpar possiveis respostas de uso interno */
unset($retorno->existe);
unset($retorno->senha);
//unset($retorno->erro); //revisar


/* Responder ao cliente */
responder($retorno);
exit; // fim

?>
