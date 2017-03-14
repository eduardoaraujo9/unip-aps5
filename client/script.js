var me={
"email":"contatoedu@outlook.com",
"senha":""
}
function login() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var res=JSON.parse(this.responseText)
      me.access_token=res.access_token;
      me.senha="";
      getConfig();
    }
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

