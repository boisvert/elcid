<?php

$debug = false;

// secure page
require('../../cgi/login.php');

debug_msg("User is logged in as $username");

// File upload details

$file = $_FILES["tutorial"];

switch ($file["type"]) {
   case 'text/xml':
      $result = save_xml_tutorial($file);
      $loc = ($result)?"file_manager.php?msg=File $result uploaded.":"overwrite_confirm.php";     
      break;
   case 'image/gif' :
   case 'image/jpeg':
   case 'image/png' :
      $result = save_media($file);
      $msg = ($result)?"File $result uploaded.":"File $result not uploaded. Is it over 1MB limit?";
      $loc = "file_manager.php?msg=$msg";      
      break;
   case 'archive/zip':
      debug_msg("zip file");
      $loc = "file_manager.php?msg=Archive support coming soon.";
      break;
   default:
      debug_msg("File type is ".$file["type"]);
      $loc = "file_manager.php?msg=Unsupported type.";
}

header("Location: $loc");


function save_xml_tutorial(&$file) {

   global $username;

   debug_msg("File type is XML");

   $tmpfile = $file["tmp_name"];

   $filename = $file["name"];

   $filepath = "users/$username"; debug_msg("Path: $filepath");
   $pathname = "$filepath/$filename";

   debug_msg("File will be saved as ../$pathname");

   // Check if file exists and if not, write the data
   
   if (file_exists("../$pathname")) {
      debug_msg("File exists - temporary storage");
      if (!is_dir("../$filepath/temp/"))
         mkdir("../$filepath/temp/");
      move_uploaded_file($tmpfile,"../$filepath/temp/$filename");
      $result = false;
   }
   else {
      move_uploaded_file($tmpfile,"../$pathname");
      debug_msg("Move succeeded");
	  
      // update database
      $filenoext = stripextension($filename);
      open_db();
      $date = date("Y-m-d");
      $sql = "INSERT INTO file (file_date, file_author, file_path, file_name)".
             " VALUES ('$date','$username','$filepath','$filenoext')".
             " ON DUPLICATE KEY UPDATE file_date='$date';";

      query_db($sql);

      $result = $filenoext;
   }
   return $result;
}

function save_media(&$file) {

   global $username;

   debug_msg("File type is supported media");

   if ($file["size"]>9999999) {
      debug_msg("File too large - no upload");
      move_uploaded_file($tmpfile,"../$filepath/temp/$filename");
      $result = false;
   }
   else {
      $tmpfile = $file["tmp_name"];
      $filename = $file["name"];
      $filepath = "../users/$username"; debug_msg("Path: $filepath");
      $pathname = "$filepath/$filename";
      debug_msg("File will be saved as $pathname");
      // Check if file exists and if not, write the data
      move_uploaded_file($tmpfile,$pathname);
      debug_msg("Move succeeded");
      $result = $filename;
   }
   return $result;
}

?>
