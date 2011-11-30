<?php
// secure page
require('login.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<title>eL-CID - Register</title>

<script type="text/javascript" src="/run/config.js"> </script>

<script language="javascript">

var pwdRequest = false;
var contactRequest = false;
var personaliaRequest = false;
var usageRequest = false;

function pwd_change() {
   var u = document.getElementById("user").value;
   var old_p = document.getElementById("old_pass").value;
   var p = document.getElementById("new_pass").value;
   var p2 = document.getElementById("new_pass_again").value;

   if (nonEmpty(old_p) && nonEmpty(p) && nonEmpty(p2)) {
      if (p == p2) {
         var params = "action=newpass" +
                      "&user=" + encodeURI( u ) +
                      "&pass=" + encodeURI( old_p ) +
                      "&newpass=" + encodeURI( p );
		 // alert("Sending "+params);
         pwdRequest = opener.POSTRequest(cgiURL+'account_save.php', params, pwdResponse);
      }
      else {
         message('The new password entries are not identical');	  
      }
   }
   else {
      message('Please enter your current username and password, and your new password');
   }
}

function pwdResponse() {
   // alert(pwdRequest.readyState);
   if (pwdRequest.readyState == 4) {
      // alert(pwdRequest.status);
      if (pwdRequest.status == 200) {
         message("Password updated");
	     //alert(pwdRequest.responseText);
      }
      else if (pwdRequest.status == 403) {
         message('Sorry, your login was not recognised.');
      }
      else {
         alert('There was a problem with the request.');
      }
   }
}

function contact_change() {
   var u = document.getElementById("user").value;
   var e_mail = document.getElementById("old_pass").value;

   if (nonEmpty(e_mail)) {
      var params = "action=newmail" +
                   "&user=" + encodeURI( u ) +
                   "&email=" + encodeURI( e_mail );
      // alert("Sending "+params);
      contactRequest = opener.POSTRequest(cgiURL+'account_save.php', params, pwdResponse);
   }
   else {
      message('Please fill in your new e-mail address.');
   }
}

function contactResponse() {
   // alert(contactRequest.readyState);
   if (contactRequest.readyState == 4) {
      // alert(contactRequest.status);
      if (contactRequest.status == 200) {
         message("E-mail updated");
	     //alert(contactRequest.responseText);
      }
      else if (contactRequest.status == 403) {
         message('Sorry, your login has expired.');
      }
      else {
         alert('There was a problem with the request.');
      }
   }
}

function personalia_change() {
   var fname = document.getElementById("firstname").value;
   var lname = document.getElementById("lastname").value;
   var country = document.getElementById("country").value;

   if (nonEmpty(fname) && nonEmpty(lname) && nonEmpty(country)) {
      var params = "action=personal" +
                   "&fname=" + encodeURI( fname ) +
                   "&lname=" + encodeURI( lname ) +
                   "&country=" + encodeURI( country );
      // alert("Sending "+params);
      personaliaRequest = opener.POSTRequest(cgiURL+'account_save.php', params, personaliaResponse);
   }
   else {
      message('Please fill in your new personal details.');
   }
}
function personaliaResponse() {
   // alert(personaliaRequest.readyState);
   if (personaliaRequest.readyState == 4) {
      // alert(pwdRequest.status);
      if (personaliaRequest.status == 200) {
         message("Personal details updated");
	 //alert(personaliaRequest.responseText);
      }
      else if (personaliaRequest.status == 403) {
         message('Sorry, your login has expired.');
      }
      else {
         alert('There was a problem with the request.');
      }
   }
}

function usage_change() {
   var edit = document.getElementById("user_editor").checked;
   var params = "action=usage" +
                   "&edit=" + encodeURI( edit );
   usageRequest = opener.POSTRequest(cgiURL+'account_save.php', params, usageResponse);
}

function usageResponse() {
   // alert(usageRequest.readyState);
   if (usageRequest.readyState == 4) {
      // alert(usageRequest.status);
      if (usageRequest.status == 200) {
         message("User level updated");
	     // alert(usageRequest.responseText);
      }
      else if (usageRequest.status == 403) {
         message('Sorry, your login has expired.');
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

function message(msg) {
   document.getElementById("message").innerHTML  = msg;
}

</script>

</head>

<body onload="message('');">

<p id="message"></p>

<form action="" method="post" name="regForm" >

 User: <?php echo $username?> <input type="hidden" id="user" value="<?php echo $username?>"> <br />

 <?
  open_db();
  $sql = "SELECT user_level, country, e_mail, first_name, last_name FROM users_tbl WHERE user_name='$username';";
  $userdata = query_one_row($sql);
 ?>
 
 <fieldset>
   <legend>Change Password</legend>
   Old password: <input type="password" id="old_pass"> <br />
   New password: <input type="password" id="new_pass"> <br />
   Repeat password: <input type="password" id="new_pass_again"> <br />
   <input type="button" value="Change" onClick= "pwd_change();">
 </fieldset>

 <fieldset>
   <legend>Change Contact information</legend>
   e-mail:  <input type="text" id="email" value="<?php echo $userdata[2]; ?>"> <br />
   <input type="button" value="Change" onClick= "email_change();">
 </fieldset>

 <fieldset>
   <legend>Change Personal Details</legend>
   First name: <input type="text" id="firstname" value="<?php echo $userdata[3]; ?>"> <br />
   Last name:  <input type="text" id="lastname" value="<?php echo $userdata[4]; ?>"> <br />
   Country:  <input type="text" id="country"  value="<?php echo $userdata[1]; ?>"> <br />
   <input type="button" value="Change" onClick= "personalia_change();">
 </fieldset>

 <fieldset>
   <legend>Change Site Usage</legend>
   <input type="checkbox" id="user_editor" checked="<?($userdata[0]==1)?'checked':'unchecked'?>"> I want to make development tutorials<br />
   <input type="button" value="Change" onClick= "usage_change();">
 </fieldset>

</form>

</body>
</html>
