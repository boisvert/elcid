<?php

// $debug = true;

// secure page
require('login.php');

debug_msg("User is logged in as $username");

// File upload details

$filename = $_FILES["tutorial"];

switch ($file["type"]) {
   case 'text/xml':
      $result = save_xml_tutorial($file);
      $loc = ($result)?"file_manager.php?msg=File $result uploaded.":"overwrite_confirm.php";
      header("Location: $loc");      
      break;
   case 'archive/zip':
      debug_msg("zip file");
      header("Location: file_manager.php?msg=Archive support coming soon.");
      break;
   default:
      debug_msg("File type is not XML, it is ".$file["type"]);
      $loc = "file_manager.php?msg=Unsupported type.";
}

header("Location: $loc");


function save_xml_tutorial(&$file) {

   global $username;

   debug_msg("File type is XML");

   $tmpfile = $file["tmp_name"];

   $filename = $file["name"];

   $filepath = "users/$username"; debug_msg("Path: $filepath");
   $pathname = "../run/$filepath/$filename";

   debug_msg("File will be saved as $pathname");

   // Check if file exists and if not, write the data
   
   if (file_exists($pathname)) {
  	   debug_msg("File exists - temporary storage");
      if (!is_dir("../run/$filepath/temp/"))
         mkdir("../run/$filepath/temp/");
      move_uploaded_file($tmpfile,"../run/$filepath/temp/$filename");
      $result = false;
   }
   else {
      move_uploaded_file($tmpfile,$pathname);
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

?>