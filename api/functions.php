<?php

/* codigo re-aproveitado da api demonstrativa em http://dev.mundoapi.com.br */

$produces = array("application/json", "application/xml", "text/plain", "text/html");

function Erro($mensagem = "Requisicao invalida ou em formato errado para essa API.", $codigo = 400, $desc="Bad Request", $arg = ""){
  /* descricao de error-codes: https://developers.google.com/doubleclick-search/v2/standard-error-responses */
  $erro->code=$codigo;
  $erro->desc=$desc;
  $erro->message=$mensagem;
  if(is_string($arg)){
    if(strlen($arg)>0){$erro->fields=$arg;}
  }else{
    if(count($arg)>0){$erro->fields=$arg;}
  }
  return $erro;
}

function converterInput(){
  if(count($_POST)>0){
    $input = json_encode($_POST);
  }else{
    $input = file_get_contents("php://input");
    switch (headerPrecedence($_SERVER['CONTENT_TYPE'])){
      case 'application/xml':
        $input = json_encode(xmlToArray($input));
    }
  }
  return json_decode($input,FALSE);
}

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

function responder($retorno){

  if(count($retorno)==0){$retorno=Erro();}
  if(isset($retorno->code)){header("HTTP/1.1 " . $retorno->code . " " . $retorno->desc);}
  else{header("HTTP/1.1 200 OK");}
  switch (getContentType()){
    case 'application/xml':
      returnXML($retorno); break;
    case 'text/html':
      returnHTML($retorno); break;
    case 'text/plain':
      returnText($retorno); break;
    default: returnJson($retorno);
  }

}
// retornos
function returnXML($retorno){
	$xml_data = new SimpleXMLElement('<?xml version="1.0"?><root></root>');
	arrayToXml($retorno, $xml_data);
	header('Content-Type: application/xml');
	print $xml_data->asXML();
}
function returnHTML($retorno){
	header('Content-Type: text/html');
	echo "<html>"
		. "<head>"
			. "<title>ChatAPI</title>"
			. folhaCSS()
		. "</head>"
		. "<body>"
			. printaArrayEmTabela($retorno)
	    . "</body>"
	  . "</html>";
}
function returnText($retorno){
	header('Content-Type: text/plain');
	var_dump($retorno);
}
function returnJson($retorno){
	header('Content-Type: application/json');
	print json_encode($retorno);
}
function printaArrayEmTabela($array, $name = "", $printHeaders = true) {
	if ($printHeaders) {
		$conteudo .= "<table class=tableHeader>"
		. "<tr>"
		. "<th class=thPrincipalKey>key</th>"
		. "<th class=thPrincipalVal>value</th>"
		. "</tr>";
	}
	foreach ($array as $key => $val) {
		if (is_array($val)) {
			$conteudo .= "<tr>"
				. "<td class=tdArray><strong>$key</strong></td>"
				. "<td><table><tr></tr>"
				. "<tr>"
				. "<th class=thHeaderKey>key</th>"
				. "<th class=thHeaderVal>value</th>"
				. "</tr>";
				printaArrayEmTabela($val, $key, false);
			$conteudo .= "</table></td>"
				. "</tr>";			
		} else {
			$conteudo .= "<tr>"
				. "<td class=tdChave>$key</td>"
				. "<td class=tdValor>$val</td>"
				. "</tr>";
		}
	}
	if ($printHeaders) {
		$conteudo .= "</table>";
	}
	return $conteudo;
}
function folhaCSS() {
	return "<style>"
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
function arrayToXml( $retorno, &$xml_data ) {
    foreach( $retorno as $key => $value ) {
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
// function to convert xml to array
function xmlToArray($input){
	return (array) simplexml_load_string($input);
}

?>
