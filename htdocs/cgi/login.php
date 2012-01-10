<?php

// database utilities
require('utils.php');

if (!$loggedin) {

   if (isset($_POST["user"])) {

      $u = $_POST["user"];
      $password = $_POST["pass"];

      // select database
      open_db();

      // Is there a user in the DB with this name & pwd?
      $sql = "SELECT * FROM users_tbl WHERE user_name='".$u."' AND pwd = '".$password."'";

      if (query_one_row($sql)) {
         debug_msg("User found.");
         $loggedin = true;
         setcookie("user", $u, time()+3600, "/");
         $sql = "UPDATE sessions_tbl SET session_user='".$u."'";
         $sql = $sql." WHERE session_key='". session_id() . "'";
         query_db($sql);
      }

   }

}

if (!$loggedin) {
      header("HTTP/1.1 403 Forbidden");
      exit();
}
?>