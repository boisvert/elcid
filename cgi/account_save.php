<?php

$debug = false;
require('login.php'); // database utilities

$action = $_POST["action"];

debug_msg("Action to take: $action");

// select database
open_db();

if ($action=="newpass") {

   debug_msg("Changing password.");
   $password = $_POST["pass"];
   $new_password = $_POST["newpass"];

   debug_msg("New password: $new_password");

   // Is there a user in the DB with this name & pwd?
   if (query_one_item("SELECT * FROM users_tbl WHERE user_name='$username' AND pwd='$password'")) {
      $sql = "UPDATE users_tbl set pwd='$new_password' WHERE user_name='$username';";
   }
   else {
      close_db();
      header("HTTP/1.1 403 Forbidden");
      exit();
   }

} // end newpass
else if ($action=="newmail") {

   debug_msg("Changing email.");
   $email = $_POST["email"];

   debug_msg("New email: $email");

   $sql = "UPDATE users_tbl set e_mail='$email' WHERE user_name='$username';";

} // end new mail
else  if ($action=="personal") {

   debug_msg("Changing personal details.");
   $fname = $_POST["fname"];
   $lname = $_POST["lname"];
   $country = $_POST["country"];

   debug_msg("New details: $fname $lname, $country");

   $sql = "UPDATE users_tbl set first_name='$fname', last_name='$fname', country='$country' WHERE user_name='$username';";

} // end new details
else if ($action=="usage") {

   debug_msg("Changing user level.");
   $edit = $_POST["edit"];

   debug_msg("New user level: edit $edit");

   if ($edit=='true') {
      $level=1;
   } else {
      $level=0;
   }
   $sql = "UPDATE users_tbl set user_level=$level WHERE user_name='$username';";
} // end usage change

query_db($sql);
close_db();

?>
