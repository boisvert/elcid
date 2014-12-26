<?php

//$debug = true;

// secure page
require('../../cgi/login.php');

debug_msg("User is logged in as $username");

if ($action = $_GET["action"]) {
   $fname = $_GET["file"];
   debug_msg( "processing $fname");
   if ($action=="Overwrite pre-existing tutorial") {
      debug_msg( "overwrite $fname");
      overwrite_old_file($fname);
   }
   remove_temp_file($fname);
}

$files = getDirectoryList("../users/$username/temp");
$num = count($files);

if ($num>0) {
   echo "$num uploaded tutorial(s) already exist. What would you like to do?";
   echo "<ul>";
   foreach ($files as $file) {
      output_form($file);
   }
   echo "</ul>";
}
else {
   header("Location: file_manager.php?msg=All files processed.");
}


function getDirectoryList ($directory) 
{
   // create an array to hold directory list
   $results = array();

   // create a handler for the directory
   $handler = opendir($directory);

   // open directory and walk through the filenames
   while ($file = readdir($handler)) {
      // if file isn't this directory or its parent, add it to the results
      if ($file != "." && $file != "..") {
        $results[] = $file;
      }
   }

   // tidy up: close the handler
   closedir($handler);

   // done!
   return $results;
}


function output_form($file) {
?>
   <form action="" method="get"> <li>
      <?php echo $file ?>
      <input type="hidden" name="file" value="<?php echo $file ?>" />
      <input type="submit" name="action" value="Overwrite pre-existing tutorial" />
      <input type="submit" name="action" value="Discard new tutorial" />
   </li> </form>
<?php
}


function remove_temp_file($fname) {
   debug_msg("delete $fname");

   global $username;
   $pathname = "../users/$username/temp/$fname";

   debug_msg("File will be saved as $pathname");

   // move the file
   chmod($pathname, 0666);
   unlink($pathname);

   debug_msg("Removal succeeded");
}


function overwrite_old_file($fname) {
   debug_msg( "overwrite $fname");

   global $username;
   $filepath = "users/$username";
   $pathname = "../$filepath/$fname";

   debug_msg("File will be saved as $pathname");

   // move the file

   move_uploaded_file("../$filepath/temp/$filename",$pathname);

   debug_msg("Move succeeded");
	  
	// update database
	$filenoext = stripextension($fname);
   open_db();
   $date = date("Y-m-d");
   $sql = "INSERT INTO file (file_date, file_author, file_path, file_name)".
          " VALUES ('$date','$username','$filepath','$filenoext')".
          " ON DUPLICATE KEY UPDATE file_date='$date';";

   query_db($sql);
}

?>
