<?php

// debug mode. Uncomment to get debug messages on screen.
//$debug = true;

// database and debug functionality
require_once('utils.php');

$file = $_GET["file"];
debug_msg("Parsing file info: ".$file.", ".strlen($file)." characters");

// Parse file to find name and path

$fname_pos = strrpos($file,"/");
debug_msg("file name found at character no: ".$fname_pos);

$file_name = substr($file,$fname_pos+1,strlen($file)-$fname_pos-5);

$start_path = 0;
// if editor is being used, then look for a leading ../ and remove from path
if (isset($_GET["editor"]))
   if ($_GET["editor"] == "on")
	   if (strpos($file,"../")==0)
	      $start_path = 3;

// path is everything up to the final /
$file_path = substr($file,$start_path,$fname_pos-$start_path);

debug_msg("Looking for file (name: ".$file_name."; path: ".$file_path.")");

// select database  
open_db();

// (1) Check if the file is known in the DB, if not, record it 
// Is there a file in the DB with this name & path?
$sql = "SELECT round(avg(rate)) as rating FROM file_rating WHERE file_id = (SELECT file_id FROM file WHERE file_name='$file_name' AND file_path='$file_path');";

$rating = query_one_item($sql);

close_db(); // on ferme la connexion

if ($rating=='0') {
  $stars="noStars";
} else if ($rating=='1') {
   $stars="oneStar";
} else if ($rating=='2') {
   $stars="twoStars";
} else if ($rating=='3') {
   $stars="threeStars";
} else if ($rating=='4') {
   $stars="fourStars";
} else if (!$rating) {
   debug_msg("File not rated.");
   $stars="notRated";
}

debug_msg("Displaying the file ".$stars);

if (!$debug)
{
   header("Location: $htURL"."run/images/$stars.png");
}

?>