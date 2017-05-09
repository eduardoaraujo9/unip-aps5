function enviar(){
	if(document.getElementById('textInput').value.trim().length>0){
		if(!sending){
			var envio={};
			envio.dados=document.getElementById('textInput').value;
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function(){
				if (this.readyState == 4 && this.status == 403) {
					fazerLogin();
					enviar();
				} else if (this.readyState == 4 && this.status == 200) {
					var d=document.createElement("div");
					d.className="box me"
					d.innerHTML="<p>" + document.getElementById('textInput').value;
					document.getElementById('textInput').value = "";
					document.getElementById('chat').appendChild(d)
					var div = document.getElementById("wrapper");
					$('#wrapper').animate({scrollTop: div.scrollHeight - div.clientHeight}, 500);
					var res=JSON.parse(this.responseText);
					if(res.lastupdate>me.lastupdate){me.lastupdate=res.lastupdate}
				}				
				sending=false;
			};
			xhttp.open("POST", api_url + "/msg?_=" + rnd(), true);
			xhttp.setRequestHeader("Content-type","application/json");
			xhttp.setRequestHeader("Accept","application/json");
			xhttp.setRequestHeader("access_token",me.access_token);
			xhttp.send(JSON.stringify(envio));
		}
	}else{
		document.getElementById('textInput').value = "";
	}
	document.getElementById('textInput').focus();
}

function receber(){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 403) {
			fazerLogin();
		} else if (this.readyState == 4 && this.status == 200) {
			var res=JSON.parse(this.responseText);
			if(!res.hasOwnProperty('lastupdate')){
				for(i=0;i<res.length;i++){
					var d=document.createElement("div");
					//default:
					d.className="box"
					d.innerHTML="<p>" + res[i]["nome"] + ": " + res[i]["dados"];
					if(res[i]["tipo"]==2){
						d.className="box img"
						d.innerHTML="<img src=" + res[i]["dados"] + ">";
						d.addEventListener("click",lightbox);
					}
					document.getElementById('chat').appendChild(d)
					//document.getElementById('chat').scrollTop=document.getElementById('chat').scrollHeight;
					me.lastupdate=res[i].lastupdate;
					if(heat<20){heat++}
				}
				var div = document.getElementById("wrapper");
				$('#wrapper').animate({scrollTop: div.scrollHeight - div.clientHeight}, 500);
			}else{
				if(heat>0){heat--}
			}
			proximoReceber();
		}				
	};
	xhttp.open("GET", api_url + "/msg?_=" + rnd(), true);
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
me.nome="";
me.access_token=getCookie("access_token");
var atualizaChat="";
var sending=false;
var heat=0;
var d=new Date();
var api_url = location.href.replace("index.html","") + "api";
function rnd(){
	return btoa(d.getTime() + Math.random());
}

function login(){
	me={};
	me.nome="";
	me.email=document.getElementById('username').value;
	me.senha=document.getElementById('password').value;
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var res=JSON.parse(this.responseText)
			me.access_token=res.access_token;
			document.cookie = "access_token="+me.access_token;
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
	xhttp.open("POST", api_url + "/login?_=" + rnd(), true);
    xhttp.setRequestHeader("Content-type","application/json");
    xhttp.setRequestHeader("Accept","application/json");
    xhttp.send(JSON.stringify(me));
    return false;
}
function getConfig(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 403) {
			fazerLogin();
		} else if (this.readyState == 4 && this.status == 200) {
			res=JSON.parse(this.responseText);
			if(res.nome.trim().length>0){me.nome=res.nome;receber()}
			else{fazerPerfil()}
			me.lastupdate=res.lastupdate;
		}
	}
	xhttp.open("GET", api_url + "/config/perfil?_=" + rnd(), true);
    xhttp.setRequestHeader("access_token",me.access_token);
    xhttp.setRequestHeader("Accept","application/json");
    xhttp.send();
}

var debug=[];
function ajaxup(){
	var formData = new FormData();
	var file = document.getElementById('anexo').files[0];
	formData.append('file[]', file, file.name);

	var xhttp = new XMLHttpRequest();

	xhttp.onreadystatechange = function(){
		if (this.readyState == 4){
			res=JSON.parse(this.responseText);
			var d=document.createElement("div");
			d.className="box img me"
			d.innerHTML="<img src=" + res.dados + ">";
			d.addEventListener("click",lightbox);
			//function programaLightbox(){var a=document.querySelectorAll(".card .foto");for(i=0;i<a.length;i++){a[i].addEventListener("click",lightbox)}}

			document.getElementById('anexo').value="";
			document.getElementById('chat').appendChild(d)
			var div = document.getElementById("wrapper");
			$('#wrapper').animate({scrollTop: div.scrollHeight - div.clientHeight}, 500);
			var res=JSON.parse(this.responseText);
			if(res.lastupdate>me.lastupdate){me.lastupdate=res.lastupdate}
			debug=this;		
			//console.log("this.readyState:" + this.readyState + " status=" + this.status + " response=" + this.responseText);
		}

	}

	xhttp.open('POST', api_url + "/envio?_=" + rnd(), true);
    xhttp.setRequestHeader("access_token",me.access_token);
    xhttp.setRequestHeader("Accept","application/json");

	xhttp.send(formData);

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
			receber(); /* atenção aqui vai dar problema na hora de salvar os logins */
			if (this.readyState == 4 && this.status == 403) {
				fazerLogin();
			}
		};
		xhttp.open("POST", api_url + "/config/perfil?_=" + rnd(), true);
		xhttp.setRequestHeader("Content-type","application/json");
		xhttp.setRequestHeader("Accept","application/json");
		xhttp.setRequestHeader("access_token",me.access_token);
		xhttp.send(JSON.stringify(me));
	}
	return false;
}

function fazerLogin(){
	me={};
	me.nome="";
	$('#login').modal({backdrop: 'static', keyboard: false});
	clearTimeout(atualizaChat);
}

function fazerPerfil(){
	if(me.nome.length>0){document.getElementById('nomePerfil').value=me.nome;}
	$('#profile').modal({backdrop: 'static', keyboard: false});
}

function proximoReceber(){
	if(heat>5){atualizaChat=setTimeout(receber,250)}
	else if(heat>4){atualizaChat=setTimeout(receber,500)}
	else if(heat>3){atualizaChat=setTimeout(receber,750)}
	else if(heat>2){atualizaChat=setTimeout(receber,1000)}
	else if(heat>1){atualizaChat=setTimeout(receber,2000)}
	else{atualizaChat=setTimeout(receber,3000)}
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function lightbox(a){var b=document.getElementById("lightbox");if(b!=null){if(b.style.opacity=="0"||b.style.opacity==""){b.style.opacity=1;b.style.height="100%";b.style.top="0";b.children[0].src=this.children[0].src}else{b.style.opacity=0;window.setTimeout(lightboxOff,500)}}}function lightboxOff(){var a=document.getElementById("lightbox");a.style.height="0";a.style.top="-100px";a.children[0].src=""}


window.onload=function(){
	if(me.access_token.length==0){fazerLogin()}
	else{getConfig()}
}
