<?php

// database utilities
require_once('utils.php');

if (!$loggedin) {

   if (isset($_POST["user"])) {

      $u = $_POST["user"];
      $password = $_POST["pass"];

      // select database
      open_db();

      // Is there a user in the DB with this name & pwd?
      $sql = "SELECT 1 FROM user WHERE user_id='".$u."' AND pwd = '".$password."'";

      if (query_one_row($sql)) {
         debug_msg("User found.");
         $loggedin = true;
         setcookie("user", $u, time()+3600, "/");
         $sql = "UPDATE session SET session_user='".$u."'";
         $sql = $sql." WHERE session_id='". session_id() . "'";
         query_db($sql);
      }
   }
}

if (!$loggedin) {
      header("HTTP/1.1 403 Forbidden");
      exit();
}
?>