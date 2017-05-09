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
    //$cliente = new Cliente();
    //$cliente->id=$token->id;
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
    //$cliente = new Cliente();
    //$cliente->id=$token->id;
    $cliente = new Cliente($token->id);
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

		$hash = "";
		
		$cliente = new Cliente($token->id);
		$chat = new Chat();

		$chat->cliente=$cliente->id;
		$chat->tipo=2;
		$chat->dados="http://unip.now.im/files/" . $hash . "/" . $name;
		$retorno = $chat->post();
		$cliente->lastupdate=$retorno->lastupdate;
		$cliente->atualizar();
		$retorno->tipo=$chat->tipo;
		$retorno->dados=$chat->dados;
		  
		//print_r($_SERVER);
	// ideias::
    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
/*    if (
        !isset($_FILES['file']['error']) ||
        is_array($_FILES['file']['error'])
    ) {
        throw new RuntimeException('Invalid parameters.');
    }
*/
/*
    // Check $_FILES['file']['error'] value.
    switch ($_FILES['file']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }
*/
    // You should also check filesize here. 
/*    if ($_FILES['file']['size'] > 1000000) {
        throw new RuntimeException('Exceeded filesize limit.');
    }
*/
    // DO NOT TRUST $_FILES['file']['mime'] VALUE !!
    // Check MIME Type by yourself.
/*    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($_FILES['file']['tmp_name']),
        array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ),
        true
    )) {
        throw new RuntimeException('Invalid file format.');
    }
*/
    // You should name it uniquely.
    // DO NOT USE $_FILES['file']['name'] WITHOUT ANY VALIDATION !!
    // On this example, obtain safe unique name from its binary data.
	
	/*
    if (!move_uploaded_file(
        $_FILES['file']['tmp_name'],
        sprintf('files/%s.%s',
            sha1_file($_FILES['file']['tmp_name']),
            $ext
        )
    )) {
        throw new RuntimeException('Failed to move uploaded file.');
    }
	*/
//move_uploaded_file($_FILES["file"]["tmp_name"], "/tmp/" . $_FILES["file"]["name"]);
//rename($_FILES["file"]["tmp_name"], "/tmp/" . $_FILES["file"]["name"]);
/*
//  5MB maximum file size 
$MAXIMUM_FILESIZE = 5 * 1024 * 1024; 
//  Valid file extensions (images, word, excel, powerpoint) 
$rEFileTypes = 
  "/^\.(jpg|jpeg|gif|png|doc|docx|txt|rtf|pdf|xls|xlsx| 
        ppt|pptx){1}$/i"; 
$dir_base = "/server/apache/vhosts/unip.nunes.net.br/CC5/APS/unip-aps5/api/files/"; 

$isFile = is_uploaded_file($_FILES['file']['tmp_name']); 
if ($isFile)    //  do we have a file? 
   {//  sanatize file name 
    //     - remove extra spaces/convert to _, 
    //     - remove non 0-9a-Z._- characters, 
    //     - remove leading/trailing spaces 
    //  check if under 5MB, 
    //  check file extension for legal file types 
    $safe_filename = preg_replace( 
                     array("/\s+/", "/[^-\.\w]+/"), 
                     array("_", ""), 
                     trim($_FILES['file']['name'])); 
    if ($_FILES['file']['size'] <= $MAXIMUM_FILESIZE && 
        preg_match($rEFileTypes, strrchr($safe_filename, '.'))) 
      {$isMove = move_uploaded_file ( 
                 $_FILES['file']['tmp_name'], 
                 $dir_base.$safe_filename);} 
      } 
   

*/

//$retorno = $name;

//    echo 'File is uploaded successfully.';

} catch (RuntimeException $e) {

//    echo $e->getMessage();
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
