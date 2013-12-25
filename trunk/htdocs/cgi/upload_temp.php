<?php

header('Content-type: text/plain');

// database and debug functionality
require('utils.php');

// Need user name, but login may have lapsed
// so username is from earlier page.

$username=$_POST["user"];

$filepath = "../run/users/".$username."/temp/";

// File upload details
$filename = $_POST["filename"];
$pathname = $filepath.$filename.".xml";

debug_msg("The file is being saved in ".$pathname);

$data = unescape($_POST["data"]);

// make temp directory if it doesn't already exist
if (!is_dir($filepath))
         mkdir($filepath);

// Open (create if needs be) the file and write the data
$file = fopen($pathname,"w");

if (file_exists($pathname)) {

   fwrite($file,$data);
   fclose($file);

   debug_msg("File written");

   echo("Temp save: $filename");
}
else { // file does not exist despite fopen
   header("HTTP/1.1 500 Internal Server Error");
   echo("\nFile could not be created");
}

?>
