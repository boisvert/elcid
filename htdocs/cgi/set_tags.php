<?php

// secure page
require('login.php');
// uncomment the line below to get execution detail
// $debug=true;
$file_id = trim($_POST["file"]);
$tags = $_POST["tags"];

if ($file_id !="") {

   $tagsArray = explode(";",$tags);
   debug_msg("Tags:".$tags);

   // open database
   open_db();

   // Remove existing tags
   $sql = "DELETE FROM file_tag WHERE (file_id=$file_id)"; 
   query_db($sql);

   foreach ($tagsArray as $tag) {

      $tag=trim($tag);
	   if ($tag!="") {

	      $sql = "INSERT INTO tag(tag,tag_author) VALUES ('$tag','$username')";
		   query_db($sql);  

         $sql = "INSERT INTO file_tag VALUES ($file_id,'$tag','$username')";
         query_db($sql);
	  }
   }

   close_db();  // on ferme la connexion

}

?>