<?php

  // definitions of database name, user, password
require_once('utils.php');

$debug=false;

$loggedin=(isset($_COOKIE['user']));

if ($loggedin)
{

   $username = $_COOKIE['user'];

   $file_id = $_POST["file"];
   $public = $_POST["public"];

   if ($file_id !="")
   {

      // select database  
      open_db();

      // Make the query
      $sql = "UPDATE file SET file_active=$public WHERE (file_id=$file_id) AND (file_author='$username')"; 

      query_db($sql);

      close_db();  // on ferme la connexion

   }
   else
      echo "sorry";

}
?>
