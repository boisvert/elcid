<?php

// secure page
require('login.php');

$file_id = trim($_POST["file"]);
$tags = $_POST["tags"];

if ($file_id !="") {

   $tagsArray = explode(";",$tags);
   debug_msg("Tags:".$tags);

   // open database
   open_db();

   // Remove existing tags
   $sql = "DELETE FROM file_tags_tbl WHERE (file_id=$file_id)"; 
   query_db($sql);

   foreach ($tagsArray as $tag) {

      $tag=trim($tag);
	  if ($tag!="") {

	     $sql = "INSERT INTO tags_tbl(tag_name,tag_author) VALUES ('$tag','$username')";
		 debug_msg("Running query: ".$sql);
	     mysql_query($sql);
         $sql = "SELECT tags_key FROM tags_tbl WHERE (tag_name='$tag')";
	     $tag_key = query_one_item($sql);	     

         $sql = "INSERT INTO file_tags_tbl VALUES ($file_id,$tag_key,'$username')";
         query_db($sql);
	  }
   }

   close_db();  // on ferme la connexion

}

?>
