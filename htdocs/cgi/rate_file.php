<?php

// debug mode. Set to true to get debug messages on screen.
$debug = false;

// database and debug functionality
require_once('utils.php');

session_start();

$file = $_GET["file"];
debug_msg("Parsing file info: ".$file.", ".strlen($file)." characters");

// Parse file to find name and path

$fname_pos = strrpos($file,"/");
debug_msg("file name found at character no: ".$fname_pos);

$file_name = substr($file,$fname_pos+1,strlen($file)-$fname_pos-5);

$start_path = 0;
// if editor is being used, then look for a leading ../ and remove from path
if ($_GET["editor"] == "on")
	if (strpos($file,"../")==0)
	   $start_path = 3;

// path is everything up to the final /
$file_path = substr($file,$start_path,$fname_pos-$start_path);

debug_msg("Looking for file (name: ".$file_name."; path: ".$file_path.")");

// select database  
open_db();

$sql = "SELECT file_id FROM file WHERE file_name='".$file_name."' AND file_path='".$file_path."' ";

$file_data = query_one_row($sql);

if ($file_data) { // update the data if the file was found
   $file_id = $file_data[0];

   $rating = $_GET['rate']; //needs validation - number

   $sql = "INSERT INTO file_rating VALUES ($file_id, '$username', $rating);";

   query_db($sql);
}

close_db(); // on ferme la connexion

?>
