/*
 Site behaviours
 by Charles Boisvert 
 21 March 2008
*/

var loginRequest = false;
var loggedin=false;
var userName = "";

function runTutorial(fileName) {
   window.location = htURL+"run/elcid.html?file="+encodeURI(fileName);
}

function login() {
   var u = document.getElementById("user").value;
   var p = document.getElementById("pass").value;
   var params = "user=" + encodeURI( u ) +
                "&pass=" + encodeURI( p );
   loginRequest = POSTRequest(cgiURL+'login.php', params, loginResponse);
}

function showLogin() {
   var element=document.getElementById("login");
   var regForm  = 'User: <input type="text" id="user">  ' +
	              'Password: <input type="password" id="pass"> ' +
                  '<input onClick="login();" type="button" value="Go"> ';
   element.innerHTML = regForm;
   document.getElementById("user").focus();
}

function POSTRequest(url, parameters, responseProcess) {
   var request = false;

   if (window.XMLHttpRequest) { // Mozilla, Safari, Opera

      request = new XMLHttpRequest();

      if (request.overrideMimeType) {
         // set type accordingly to anticipated content type
         // eg 'text/xml' or 'text/html'
         request.overrideMimeType('text/ascii');
      }
   }
	 else if (window.ActiveXObject) { // IE
      try {
         request = new ActiveXObject("Msxml2.XMLHTTP");
     } catch (e) {
        try {
           request = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {}
     }
   }

   if (!request) {
      alert('Cannot create XMLHTTP instance'); // Kludge this should raise an error
   } else {
      request.open('POST', url, true);
      request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      request.setRequestHeader("Content-length", parameters.length);
      request.setRequestHeader("Connection", "close");
      request.send(parameters);
      request.onreadystatechange = responseProcess;
   }
   return request;
}

function loginResponse() {
   if (loginRequest.readyState == 4) {
      if (loginRequest.status == 200) {
         loggedin = true;
         userName = document.getElementById("user").value;
         welcomeLogin();           
      }
      else
      {
         if (loginRequest.status == 403) {
            register();
         }
         else {
            alert('Your login could not be checked.\nPlease try again later.');
	     }
      }
   }
}

function welcomeLogin() {
   var welcome;
   if (loggedin) {
      welcome  =  'Welcome ' + userName;
      welcome += ' (<a href="" onClick="popup(cgiURL+\'account_manager.php\');">account details</a> ';
      welcome += '| <a href="' + htURL + 'run/editor/cid_editor.html">editor</a>)';
   }
   else {
      welcome = '<input onClick="showLogin();" type="button" value="Login / Register">';
   }
   document.getElementById("login").innerHTML = welcome;
}

function register() {
   var u = document.getElementById("user").value;
   var p = document.getElementById("pass").value;
   popup(cgiURL+'registration.php');
}

function popup(url) { 
   return window.open(url,'newwin','height=300,width=350,location=no,resizable=yes'); 
}
