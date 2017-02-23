<?php

/* descrição da API
 * @rest\title API demonstrativa
 * @rest\version 1.0.0
 * @rest\description ## ![API Logo](demoapi.png)<br><br>API RESTful para testes diversos e provas de conceitos.
 * @rest\contact http://dev.mundoapi.com.br/doc eduardo.araujo@mundoapi.com.br Eduardo Araujo
 * @rest\license artistic
 * @rest\security api_key apiKey access_token header Token de autentica&ccedil;&atilde;o, usar a chave `test-key`.
 * @rest\require api_key
 * @rest\schemes http
 * @rest\consumes application/x-www-form-urlencoded
 * @rest\produces application/json application/xml text/plain text/html
 */
        $produces = array("application/json", "application/xml", "text/plain", "text/html");
/** modelos de dados retornados:
 * @rest\model Funcionario
 * @rest\property int id C&oacute;digo de identifica&ccedil;&atilde;o do funcion&aacute;rio
 * @rest\property string nome Nome do funcion&aacute;rio
 * @rest\property string telefone Telefone do funcion&aacute;rio
 * @rest\property string cargo Cargo do funcion&aacute;rio
 * @rest\model Funcionarios
 * @rest\property int id C&oacute;digo de identifica&ccedil;&atilde;o do funcion&aacute;rio
 * @rest\property string nome Nome do funcion&aacute;rio
 * @rest\model Cargos
 * @rest\property string cargo Cargo do funcion&aacute;rio
 * @rest\model Header
 * @rest\property string chave Nome da chave do cabe&ccedil;alho
 * @rest\property string valor Valor da chave do cabe&ccedil;alho
 * @rest\model Token
 * @rest\property string access_token Token de acesso do usu&aacute;rio
 * @rest\model Erro
 * @rest\property int codigo C&oacute;digo do erro
 * @rest\property string erro Erro ocorrido
 * @rest\property string mensagem Descri&ccedil;&atilde;o do erro
 */ 

 		/*
		* @rest\api Login Valida as credenciais de acesso.
		*/

if ($_GET['tipo'] == 'login') {
	/**
	* @rest\endpoint /login
	* @rest\tag Login Fun&ccedil;&otilde;es referentes aos funcion&aacute;rios.
	* @rest\method POST Listar todos os funcion&aacute;rios
	* @rest\description Retorna o token de acesso com base nas credenciais de login.
	* @rest\form string login login do usu&aacute;rio
	* @rest\form string pass senha do usu&aacute;rio
	* @rest\response 200 array(Token) Credencial de acesso.
	* @rest\response 401 Erro Erro de autentica&ccedil;&atilde;o
	*/
	$data = array (
				'codigo' => 401,
				'erro' => 'auth',
				'mensagem' => 'Falha na autenticacao.',
			);
	if(strlen($_POST['login'])>0){
		$database = json_decode(file_get_contents("users.json"), true);
		foreach ($database as $row => $key) {
			if($_POST['login'] == $key['login']) {
				if($_POST['pass'] == $key['pass']) {
					$data = array ( 'access_token' => $key['token']	);
				}
				break;
			}
		}
	}
} else if ($_GET['tipo'] == 'accessToken') {
	/**
	* @rest\endpoint /accessToken
	* @rest\tag Login Fun&ccedil;&otilde;es referentes aos funcion&aacute;rios.
	* @rest\method POST Listar todos os funcion&aacute;rios
	* @rest\description Retorna o token de acesso com base nas credenciais de login.
	* @rest\form string client_id id do app
	* @rest\form string client_secret secredo do app
	* @rest\response 200 array(Token) Credencial de acesso.
	* @rest\response 401 Erro Erro de autentica&ccedil;&atilde;o
	*/
	$data = array (
				'codigo' => 401,
				'erro' => 'auth',
				'mensagem' => 'Falha na autenticacao.',
			);
	if(strlen($_POST['client_id'])>0){
		$database = json_decode(file_get_contents("users.json"), true);
		foreach ($database as $row => $key) {
			if($_POST['client_id'] == $key['client_id']) {
				if($_POST['client_secret'] == $key['client_secret']) {
					$data = array ( 'access_token' => $key['token']	);
				}
				break;
			}
		}
	}
} else if (!isKeyValid()) { $data = array (
						'codigo' => 401,
						'erro' => 'auth',
						'mensagem' => 'Falha na autenticacao.'
					);
} else {
	$database = json_decode(file_get_contents("database.json"), true);
	if ($_GET['tipo'] == 'funcionarios') {
		/*
		* @rest\api Funcionarios Mostra as informa&ccedil;&otilde;es dos funcion&aacute;rios.
		*/
		if (empty($_GET['id'])) {
			/**
			* @rest\endpoint /funcionarios
			* @rest\tag Funcionario Fun&ccedil;&otilde;es referentes aos funcion&aacute;rios.
			* @rest\method GET Listar todos os funcion&aacute;rios
			* @rest\description O recurso funcion&aacute;rios retorna uma lista com todos os funcion&aacute;rios existentes.
			* @rest\require api_key
			* @rest\response 200 array(Funcionarios) Matriz de funcion&aacute;rios
			* @rest\response 401 Erro Erro de autentica&ccedil;&atilde;o
			*/
			$data = array();
			foreach ($database as $row => $key) {
				array_push($data, array('id' => $key['id'],	'nome' => $key['nome']));
			}
		} else {
			/**
			* @rest\endpoint /funcionarios/{id}
			* @rest\tag Funcionario Fun&ccedil;&otilde;es referentes aos funcion&aacute;rios.
			* @rest\method GET Listar dados de um funcion&aacute;rio
			* @rest\description O recurso funcion&aacute;rios retorna os dados de um funcion&aacute;rio espec&iacute;fico definido pelo				par&acirc;metro {id}.
			* @rest\require api_key
			* @rest\path integer id ID do funcion&aacute;rio
			* @rest\response 200 array(Funcionario) Dados do funcion&aacute;rio
			* @rest\response 401 Erro Erro de autentica&ccedil;&atilde;o
			* @rest\response 404 Erro Erro na requisi&ccedil;&atilde;o
			*/
			$data = array();
			foreach ($database as $row => $key) {
				if ($_GET['id'] == $key['id']) {
					array_push($data, array('id' => $key['id'], 'nome' => $key['nome'],	'telefone' => $key['telefone'],	'cargo' => $key['cargo']));
					//break; //sem break: permite printar mais de um funcionario com o mesmo id
				}
			}
			if (!isset($data[0])) {
				$data = array (
					'codigo' => 404,
					'erro' => 'funcionario.id',
					'mensagem' => 'id de funcionario desconhecido',
				);
			}
		}
		/*
		* @rest\api Cargos Lista os cargos dos funcion&aacute;rios.
		*/	
	} elseif ($_GET['tipo'] == 'cargos') {
			/**
			* @rest\endpoint /cargos
			* @rest\tag Cargo Fun&ccedil;&otilde;es referentes aos funcion&aacute;rios.
			* @rest\method GET Listar todos os cargos
			* @rest\description O recurso cargos retorna uma lista de todos os cargos existentes.
			* @rest\require api_key
			* @rest\response 200 array(Cargos) Matriz de cargos
			* @rest\response 401 Erro Erro de autentica&ccedil;&atilde;o
			*/	
			$data = array();
			foreach ($database as $row => $key) {
				$add = true;
				foreach ($data as $r => $k) {
					if ($k['cargo'] == $key['cargo']) { $add = false; break; }
				}
				if ($add) { array_push($data, array('cargo' => $key['cargo'])); }
			}
		/*
		* @rest\api Debug Lista as chaves de header, get e post enviadas para a API.
		*/	
} elseif ($_GET['tipo'] == 'debug' || $_POST['tipo'] == 'debug') {
			/**
			* @rest\endpoint /debug
			* @rest\tag Debug Lista as chaves de header, get e post enviadas para a API.
			* @rest\method GET Listar todas as chaves
			* @rest\description O recurso cargos retorna uma lista de todas as chaves de cabe&ccedil;alhos, requisi&ccedil;&otilde;es GET e POST.
			* @rest\require api_key
			* @rest\response 200 array(Header) Matriz de itens
			* @rest\response 401 Erro Erro de autentica&ccedil;&atilde;o
			* @rest\method POST Listar todas as chaves
			* @rest\description O recurso cargos retorna uma lista de todas as chaves de cabe&ccedil;alhos, requisi&ccedil;&otilde;es GET e POST.
			* @rest\require api_key
			* @rest\response 200 array(Header) Matriz de itens
			* @rest\response 401 Erro Erro de autentica&ccedil;&atilde;o
			*/	
		$data = array();
		$req = getallheaders();
		$data['headers']=$req;
		$data['get']=$_GET;
		$data['post']=$_POST;
		$data['server']=$_SERVER;
	} else {
		$data = array (
			'codigo' => 404,
			'erro' => 'request',
			'mensagem' => 'requisicao ' . $_GET['tipo'] . ' desconhecida',
		);	
	}
}

if (isset($data['erro'])) { http_response_code($data['codigo']); }

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

function getContentType(){ // padrão retornar json
	if (isset($_GET['alt'])) {
		switch (strtolower($_GET['alt'])) {
			case 'text':
			case 'plain':
				return "text/plain";
			case 'html':
				return "text/html";
			case 'xml':
				return "application/xml";
			default:
				return "application/json"; 
		}
	} elseif (isset($_SERVER['HTTP_ACCEPT'])) { // header: Accept
		return headerPrecedence($_SERVER['HTTP_ACCEPT']);
	} elseif (isset($_SERVER['CONTENT_TYPE'])) { // header: Content-Type
		return headerPrecedence($_SERVER['CONTENT_TYPE']);
	} else { return "json"; } // no headers found, retorna padrão json
	/*
	
		* Accept: is what the browser is able to digest, for example, all the languages someone can understand.
		* Content-Type: is what format the actual data is in, for example what language someone is speaking. Since computers can't (well, now they can) recognize other types like people can say "oh, he's German!" or "she's speaking Chinese!"
	
	então, preferência por Accept, o Content-Type é mais para quem envia dados para a API pelos verbos PUT/POST ...
	Content-Type vai ser fallback do Accept. */
}

function headerPrecedence($header){
	global $produces;
	$headers = explode(",",$header);
	$total = count($headers);
	$lista=array();
	foreach ($headers as $ordem => $tipo) {
		$mod = explode(";", $tipo);
		$key = $mod[0];
		$modificador = 100;
		foreach ($mod as $i => $tmp) {
			if ($i>0) {
				$teste = explode("=", $tmp);
				if ($teste[0] == "q") { $modificador *= $teste[1]; }
			}
		}
		$lista[$key]=$modificador + ($total - $ordem);
	}
	foreach ($lista as $key => $peso){ if (in_array($key, $produces)){return $key;} }	
}

// retornos
function returnXML(){
	global $data;
	$xml_data = new SimpleXMLElement('<?xml version="1.0"?><root></root>');
	arrayToXml($data, $xml_data);
	header('Content-Type: application/xml');
	print $xml_data->asXML();
}
function returnHTML(){
	global $data;
	header('Content-Type: text/html');
	echo "<html>"
		. "<head>"
			. "<title>API demo</title>"
			. folhaCSS()
		. "</head>"
		. "<body>"
			. printaArrayEmTabela($data)
	    . "</body>"
		. "<footer></footer>"
	  . "</html>";
}
function returnText(){
	global $data;
	header('Content-Type: text/plain');
	var_dump($data);
}
function returnJson(){
	global $data;
	header('Content-Type: application/json');
	print json_encode($data);
}

function printaArrayEmTabela($array, $name = "", $printHeaders = true) {
	if ($printHeaders) {
		echo "<table class=tableHeader>"
		. "<tr>"
		. "<th class=thPrincipalKey>key</th>"
		. "<th class=thPrincipalVal>value</th>"
		. "</tr>";
	}
	foreach ($array as $key => $val) {
		if (is_array($val)) {
			echo "<tr>"
				. "<td class=tdArray><strong>$key</strong></td>"
				. "<td><table><tr></tr>"
				. "<tr>"
				. "<th class=thHeaderKey>key</th>"
				. "<th class=thHeaderVal>value</th>"
				. "</tr>";
				printaArrayEmTabela($val, $key, false);
			echo "</table></td>"
				. "</tr>";			
		} else {
			echo "<tr>"
				. "<td class=tdChave><wbr>$key</wbr></td>"
				. "<td class=tdValor>$val</td>"
				. "</tr>";
		}
	}
	if ($printHeaders) {
		echo "</table>";
	}
}

function folhaCSS() {
	echo "<style>"
		. "table{border-collapse:collapse;width:100%;max-width:665px;border-style:hidden}"
		. "table table{max-width:100%}"
		. "td,th{border-bottom:1px solid black;border-right:1px solid black}"
		. ".tableHeader{border:1px solid black}"
		. ".thPrincipalKey{background-color:#90C3D4}"
		. ".thPrincipalVal{background-color:#90C3D4;text-align:left;padding-left:100px}"
		. ".thHeaderKey{background-color:#E0E0E0}"
		. ".thHeaderVal{background-color:#E0E0E0;text-align:left;padding-left:15px}"
		. ".tdChave{text-align:center;width:20%}"
		. ".tdArray{background-color:#60828D;color:#fff;text-align:center}"
		. ".tdValor{padding-left:15px}"
		. "</style>";
}

// function to convert array to xml
function arrayToXml( $data, &$xml_data ) {
    foreach( $data as $key => $value ) {
        if( is_array($value) ) {
            if( is_numeric($key) ){
                $key = 'item'.$key; //dealing with <0/>..<n/> issues
            }
            $subnode = $xml_data->addChild($key);
            arrayToXml($value, $subnode);
        } else {
            $xml_data->addChild("$key",htmlspecialchars("$value"));
        }
     }
}

// validação de chaves de acesso
function isKeyValid() {
	if (isset($_GET['access_token'])) {  // chave via GET
		$key = $_GET['access_token'];
	} elseif (isset($_GET['api_key'])) { // chave via GET - swagger
		$key = $_GET['api_key'];
	} else {                             // chave via HEADER
		$req = getallheaders();
		if (isset($req['access_token'])) {
			$key = $req['access_token'];
		} elseif (isset($req['api_key'])) { // swagger
			$key = $req['api_key'];
		}
	}
	if (isset($key)) { 
		if ($key == 'apigee-key-a1b2c3') { return true; }
		if ($key == 'test-key') { return true; }
		$database = json_decode(file_get_contents("users.json"), true);
		foreach ($database as $row => $db) { if($key == $db['token']) { return true; } }
	}
	return false;
}

?>

