function enviar(){
	if(document.getElementById('textInput').value.length>0){
		var envio={};
		envio.dados=document.getElementById('textInput').value;
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 403) {
				fazerLogin();
			} else if (this.readyState == 4 && this.status == 200) {
				var d=document.createElement("div");
				d.className="box me"
				d.innerHTML="<p>" + document.getElementById('textInput').value;
				document.getElementById('textInput').value = "";
				document.getElementById('chat').appendChild(d)
				document.getElementById('chat').scrollTop=document.getElementById('chat').scrollHeight;
				/*var objDiv = document.getElementById("chat");
				objDiv.scrollTop = objDiv.scrollHeight;*/
				var div = document.getElementById("chat");
				$('#chat').animate({scrollTop: div.scrollHeight - div.clientHeight}, 500);
				var res=JSON.parse(this.responseText);
				if(res.lastupdate>me.lastupdate){me.lastupdate=res.lastupdate}
			}				
		};
		xhttp.open("POST", "http://unip.nunes.net.br/CC5/APS/unip-aps5/api/msg?_=" + rnd, true);
		xhttp.setRequestHeader("Content-type","application/json");
		xhttp.setRequestHeader("Accept","application/json");
		xhttp.setRequestHeader("access_token",me.access_token);
		xhttp.send(JSON.stringify(envio));
	}
	document.getElementById('textInput').focus();
}

function receber(){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 403) {
			fazerLogin();
		} else if (this.readyState == 4 && this.status == 200) {
			var div = document.getElementById("chat");
			$('#chat').animate({scrollTop: div.scrollHeight - div.clientHeight}, 500);
			var res=JSON.parse(this.responseText);
			for(i=0;i<res.length;i++){
				var d=document.createElement("div");
				d.className="box"
				d.innerHTML="<p>" + res[i]["nome"] + ": " + res[i]["dados"];
				document.getElementById('textInput').value = "";
				document.getElementById('chat').appendChild(d)
				document.getElementById('chat').scrollTop=document.getElementById('chat').scrollHeight;
			}
				/* JSON response:
				  {
					"lastupdate": "1",
					"hora": "2017-03-06 21:06:21",
					"nome": "Eduardo Araujo",
					"tipo": "0",
					"dados": "mensagem de teste"
				  },
				*/

		}				
	};
	xhttp.open("GET", "http://unip.nunes.net.br/CC5/APS/unip-aps5/api/msg?_=" + rnd, true);
	xhttp.setRequestHeader("Content-type","application/json");
	xhttp.setRequestHeader("Accept","application/json");
	xhttp.setRequestHeader("access_token",me.access_token);
	xhttp.send();
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

var me={};
var rnd="";
var atualizaChat="";
var d=new Date();

function login() {
	rnd=btoa(d.getTime() + Math.random());
	me={}
	me.email=document.getElementById('username').value;
	me.senha=document.getElementById('password').value;
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var res=JSON.parse(this.responseText)
			me.access_token=res.access_token;
			delete me.senha;
			$('#login').modal('hide');
			document.getElementById('username').value="";
			document.getElementById('password').value="";
			getConfig();
		} else if (this.readyState == 4 && this.status == 403) {
			loginErro();
		} /*else {
			console.log("this.readyState:" + this.readyState + " status=" + this.status);
		}*/
    };
    xhttp.open("POST", "http://unip.nunes.net.br/CC5/APS/unip-aps5/api/login?_=" + rnd, true);
    xhttp.setRequestHeader("Content-type","application/json");
    xhttp.setRequestHeader("Accept","application/json");
    xhttp.send(JSON.stringify(me));
}
function getConfig(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			res=JSON.parse(this.responseText);
			if(res.nome.length>0){me.nome=res.nome;fazerAtualizacao()}
			else{fazerPerfil()}
			me.lastupdate=res.lastupdate;
		}
    };
    xhttp.open("GET", "http://unip.nunes.net.br/CC5/APS/unip-aps5/api/config/perfil?_=" + rnd, true);
    xhttp.setRequestHeader("access_token",me.access_token);
    xhttp.setRequestHeader("Accept","application/json");
    xhttp.send();
}

function salvarPerfil(){
	if(document.getElementById("nomePerfil").value.length<4){
		if(document.getElementById("perfilErro")==null){
			var d=document.createElement("div");
			d.className="alert alert-danger"
			d.id="perfilErro"
			d.setAttribute("role", "alert");
			d.innerHTML="<strong>Nome inválido</strong>"
			document.querySelector("#profile .modal-dialog .modal-content .modal-footer").prepend(d)
			document.getElementById('profile').className+=" shake";
		}else{
			document.getElementById('profile').className+=" shake";
		}
		window.setTimeout(function(){document.getElementById('profile').className=document.getElementById('profile').className.replace("shake","").trim()},1000);
	}else{
		me.nome=document.getElementById("nomePerfil").value;
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			$('#profile').modal('hide');
			document.getElementById("nomePerfil").value="";
			fazerAtualizacao();
			if (this.readyState == 4 && this.status == 403) {
				fazerLogin();
			}
		};
		xhttp.open("POST", "http://unip.nunes.net.br/CC5/APS/unip-aps5/api/config/perfil?_=" + rnd, true);
		xhttp.setRequestHeader("Content-type","application/json");
		xhttp.setRequestHeader("Accept","application/json");
		xhttp.setRequestHeader("access_token",me.access_token);
		xhttp.send(JSON.stringify(me));
	}
}

function fazerLogin(){
	$('#login').modal({backdrop: 'static', keyboard: false});
	clearInterval(atualizaChat);
}

function fazerPerfil(){
	$('#profile').modal({backdrop: 'static', keyboard: false});
}

function fazerAtualizacao(){
	atualizaChat = setInterval(receber, 3000);
}

window.onload=function(){
	fazerLogin();
  //$('#myModal').modal({show:'false'}); 	
	
}