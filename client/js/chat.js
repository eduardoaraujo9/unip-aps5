function enviar(){
	/* obs: faz chamada da API, se retornar OK, então faz o processo abaixo */
	/* se der erro (timeout) pode por exemplo, abrir a tela de login de novo */
	var d=document.createElement("div");
	d.className="box"
	d.innerHTML="<p>" + document.getElementById('textInput').value;
	document.getElementById('textInput').value = "";
	document.getElementById('chat').appendChild(d)
	document.getElementById('chat').scrollTop=document.getElementById('chat').scrollHeight;
}

function loginErro(){
	if(document.getElementById("loginErro")==null){
		var d=document.createElement("div");
		d.className="alert alert-danger"
		d.id="loginErro"
		d.setAttribute("role", "alert");
		d.innerHTML="<strong>Login Incorreto</strong>"
		document.querySelector("#login .modal-dialog .modal-content .modal-footer").prepend(d)
        document.getElementById('login').className+=" shake";
	}else{
        document.getElementById('login').className+=" shake";
	}
	window.setTimeout(function(){document.getElementById('login').className=document.getElementById('login').className.replace("shake","").trim()},1000);
}

var me={"email":"","senha":""}
function login() {
	me.email=document.getElementById('username').value;
	me.senha=document.getElementById('password').value;
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var res=JSON.parse(this.responseText)
			me.access_token=res.access_token;
			me.senha="";
			getConfig();
			document.getElementById('username').value="";
			document.getElementById('password').value="";
			$('#login').modal('hide');
		} else if (this.readyState == 4 && this.status == 403) {
			loginErro();
		} /*else {
			console.log("this.readyState:" + this.readyState + " status=" + this.status);
		}*/
    };
    xhttp.open("POST", "http://unip.nunes.net.br/CC5/APS/unip-aps5/api/login", true);
    xhttp.setRequestHeader("Content-type","application/json");
    xhttp.setRequestHeader("Accept","application/json");
    xhttp.send(JSON.stringify(me));
}
function getConfig(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			res=JSON.parse(this.responseText);
			if(res.nome.length>0){me.nome=res.nome}
			else{console.log("Anonimo!")}
			me.lastupdate=res.lastupdate;
			console.log("done!");
		}
    };
    xhttp.open("GET", "http://unip.nunes.net.br/CC5/APS/unip-aps5/api/config/perfil", true);
    xhttp.setRequestHeader("access_token",me.access_token);
    xhttp.setRequestHeader("Accept","application/json");
    xhttp.send();
}

window.onload=function(){
	$('#login').modal({backdrop: 'static', keyboard: false})  ;
  //$('#myModal').modal({show:'false'}); 	
	
}