<?php

  // definitions of database name, user, password
require('utils.php');

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
      $sql = "UPDATE files_tbl SET file_active=$public WHERE (file_key=$file_id) AND (file_author='$username')"; 

      query_db($sql);

      close_db();  // on ferme la connexion

   }
   else
      echo "sorry";

}
?>
