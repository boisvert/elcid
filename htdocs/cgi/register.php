<?php
// database utilities
require('utils.php');

$username = $_POST["user"];
$firstname = $_POST["firstname"];
$lastname = $_POST["lastname"];
$password = $_POST["pass"];
$password2 = $_POST["pass_again"];
$email = $_POST["email"];
$country = $_POST["country"];
	
// select database  
open_db();

// Is there a user in the DB with this name & pwd?
$sql = "SELECT * FROM users_tbl WHERE user_name='".$username."'";

if (query_one_item($sql))
   {
      echo("The name <i><?php echo($username); ?></i> is already taken. Find an alternative name.");
   }
   else
   {
      $sql = "INSERT INTO users_tbl VALUES('$country','$email','$firstname','$lastname','$password','$username',0)";
      query_db($sql);
	  mkdir("../run/users/$username", 0600, true);
	  echo("Your registration is recorded.");
   }    

?>
