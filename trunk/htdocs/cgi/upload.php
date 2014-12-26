<?php

header('Content-type: text/plain');

// secure page
require('login.php');
// $debug=true;

debug_msg("User is logged in as ".$username);

$filepath = "users/".$username;

// File upload details
$filename = $_POST["filename"];
$pathname = "../run/".$filepath."/".$filename.".xml";

debug_msg("The file is being saved in ".$pathname);

$data = unescape($_POST["data"]);

// Open (create if needs be) the file and write the data
$file = fopen($pathname,"w");

if (file_exists($pathname)) {

   fwrite($file,$data);
   fclose($file);

   debug_msg("File written");

   // select database
   open_db();

   // Is there a file in the DB with this name & path?
   $sql = "SELECT file_id FROM file WHERE file_name='$filename' AND file_path='$filepath';";

   $date = date("Y-m-d");
   $reply = "File $filename ";

   if ($key = query_one_item($sql)) {
      $sql = "UPDATE file SET file_date='".$date."' WHERE file_id = ".$key.";";
      $reply .= "updated.";
   }
   else {
      $sql = "INSERT INTO file (file_date, file_author, file_path, file_name) ".
                    "VALUES ('".$date."','".$username."','".$filepath."','".$filename."')";
      $reply .= "created.";
   }

   query_db($sql);

   // if a temp save exists, delete it

   $pathname = "../run/users/$username/temp/$filename.xml";
   if (file_exists($pathname)) {
      unlink($pathname);
      debug_msg("Temp save removed");
   }

   echo($reply."\n");
   echo("../users/$username/$filename.xml");
}
else { // file does not exist despite fopen
   header("HTTP/1.1 500 Internal Server Error");
   echo("\nFile could not be created");
}

?>
