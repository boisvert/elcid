<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<title>eL-CID - Register</title>

<script type="text/javascript" src="/run/config.js"> </script>

<script language="javascript">

var loginRequest = false;
var regRequest = false;

function fillin() {
   document.getElementById("user").value = opener.document.getElementById("user").value;
   document.getElementById("pass").value = opener.document.getElementById("pass").value;
}

function message(msg) {
   document.getElementById("message").innerHTML  = msg;
}

function login() {
   var u = document.getElementById("user").value;
   var p = document.getElementById("pass").value;
   
   if (nonEmpty(u) && nonEmpty(p)) {
      var params = "user=" + encodeURI( u ) +
                   "&pass=" + encodeURI( p );
      loginRequest = opener.POSTRequest(cgiURL+'login.php', params, loginResponse);
   }
   else
   {
      alert('Please fill in the username and password');
   }
}

function loginResponse() {
   if (loginRequest.readyState == 4) {
      if (loginRequest.status == 200) {
         opener.loggedIn(loginRequest.responseText);
         close();
      }
      else
      {
         if (loginRequest.status == 403) {
            message('Sorry, username and password still not recognised. Try again or register:');
         }
         else
            alert('There was a problem with the request.');
      }
   }
}

function register() {

   var p = document.getElementById("pass").value;
   var p2 = document.getElementById("pass_again").value;

   if (p==p2) {
      var u = document.getElementById("user").value;
      var f = document.getElementById("firstname").value;
      var l = document.getElementById("lastname").value;
      var e = document.getElementById("email").value;
      var c = document.getElementById("country").value;
	  valid = nonEmpty(u) && nonEmpty(f) && nonEmpty(l) && nonEmpty(e) && nonEmpty(c);
	  if (valid) {
         var params = "user=" + encodeURI( u ) +
                      "&firstname=" + encodeURI( f ) +
                      "&lastname=" + encodeURI( l ) +
                      "&pass=" + encodeURI( p ) +
                      "&pass_again=" + encodeURI( p2 ) +
                      "&email=" + encodeURI( e ) +
                      "&country=" + encodeURI( c );
         regRequest = opener.POSTRequest(cgiURL+'register.php', params, regResponse);
      }
	  else
	  {
		  message("Please check the registration form");
	  }
   }
   else {
	  message ("The passwords are not identical"); 
   }	   
}

function regResponse() {
   if (regRequest.readyState == 4) {
      if (regRequest.status == 200) {
         message(regRequest.responseText);           
      }
      else {
         alert('There was a problem with the request.');
      }
   }
}

function nonEmpty(str) {
	result = (str != "" )
	return result;
}

</script>

</head>

<body onload="fillin(); message('Username and password not recognised. Try again or register:');">

<form action="register.php" method="post" name="regForm" >
 <p id="message"></p>

<table>
<tr> <td>User: </td> <td><input type="text" id="user"></td> </tr>
<tr>
   <td>Password: </td>
   <td>
      <input type="password" id="pass">
	  <input type="button" value="Login" onClick= "login();">
   </td>
</tr>
<tr> <td>Repeat password: </td> <td><input type="password" id="pass_again"></td> </tr>
<tr> <td>First name: </td> <td><input type="text" id="firstname"></td> </tr>
<tr> <td>Last name: </td> <td><input type="text" id="lastname"></td> </tr>
<tr> <td>e-mail: </td> <td><input type="text" id="email"></td> </tr>
<tr> <td>country: </td> <td><input type="text" id="country"></td> </tr>
</table>
<input type="button" value="Register" onClick="register();"> <br />
</form>

</body>
</html>
