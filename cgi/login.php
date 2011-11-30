<?php

// database utilities
require('utils.php');

if (!$loggedin) {

   $u = $_POST["user"];
   $password = $_POST["pass"];

   // select database
   open_db();

   // Is there a user in the DB with this name & pwd?
   $sql = "SELECT * FROM users_tbl WHERE user_name='".$u."' AND pwd = '".$password."'";

   if (query_one_item($sql)) {
      $loggedin = true;
	   $username = $u;
      setcookie("user", $username, time()+3600, "/");
      $sql = "UPDATE sessions_tbl SET session_user='".$username."'";
      $sql = $sql." WHERE session_key='". session_id() . "'";
      query_db($sql);
   } else {
      header("HTTP/1.1 403 Forbidden");
	   exit();
   }
}
?>