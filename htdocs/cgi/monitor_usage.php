<?php

// debug mode. Uncomment to get debug messages on screen.
//$debug = true;

// database and debug functionality
require('utils.php');

$time = date("H:i:s");

$file_use_key = $_GET["fileuseid"];
debug_msg("Looking for file use (No. ".$file_use_key.")");

$editor = $_GET["editor"];
if (!$editor == 'on') $editor = 'off';
debug_msg("Editor: ".$editor);

// strrpos($file,"/");

$file_name = $_GET["fname"];

// path is everything up to the final /
$file_path = substr($_GET["fpath"], 0, -1);

debug_msg("Looking for file (name: ".$file_name."; path: ".$file_path.")");

// select database  
open_db();

// (1) Check if the file is known in the DB, if not, record it 
// Is there a file in the DB with this name & path?
$sql = "SELECT file_key FROM files_tbl WHERE file_name='".$file_name."' AND file_path='".$file_path."' ";

// Get file_key. If file_use has not been recorded before...
if (!($file_key = query_one_item($sql)))
{
   debug_msg("File not yet recorded. Recording now.");
   $sql = "INSERT INTO";
   $sql = $sql." files_tbl (file_name, file_path, file_active)";
   $sql = $sql." VALUES ('".$file_name."','".$file_path."', 0)";

   query_db($sql);

   $file_key = query_one_item("SELECT MAX(file_key) FROM files_tbl");
} // finish recording the file

// (2) Check if file_use (generated by javascript) is in the DB.
// If file_use has been recorded, select corresponding session
$sql = "SELECT session_id FROM file_uses_tbl WHERE file_use_key='".$file_use_key."'";

// Get session_key. If file_use has not been recorded before, session_key is not found here
if (!($session_key = query_one_item($sql)))
{
   // In that case, get session key through php session management
   debug_msg("File use not yet recorded.");
   $session_key = session_id();

   // If file_use is not in the DB, session_id may not be either.
   // Is there a session in the DB with this key?
   debug_msg("Looking for session (No. ".$session_key.")");
   $sql = "SELECT count(*) FROM sessions_tbl WHERE session_key='".$session_key."'";

   // If not, record it now
   $session_not_recorded = (query_one_item($sql)==0);
   if ($session_not_recorded)
   {
	  debug_msg("Session not yet recorded. Recording now.");
      $client_ip = $_SERVER['REMOTE_ADDR'];
      $remote_host = $_SERVER['REMOTE_HOST'];
	  $client_desc = $_SERVER['HTTP_USER_AGENT'];

 	  $date = date("Y-m-d");

      $sql = "INSERT INTO";
      $sql = $sql." sessions_tbl (client_description, client_ip, client_host_name, session_date, session_time, session_key)";
      $sql = $sql." VALUES ('".$client_desc."','".$client_ip."','".$remote_host."',";
      $sql = $sql."'".$date."','".$time."','".$session_key."')";

	  query_db($sql);

   } // finish recording the new session

   // Now record the file_use for this session

   debug_msg("Recording the file use.");

   $sql = "INSERT INTO";
   $sql = $sql." file_uses_tbl (file_id, file_use_key, session_id, file_use_edit, file_use_time)";
   $sql = $sql." VALUES (".$file_key.",'".$file_use_key;
   $sql = $sql."','".$session_key."','".$editor."','".$time."')";

   query_db($sql);

} // finish recording the file_use

// record the command
$command = $_GET["command"];

debug_msg("Recording the user command: ".$command);

$sql = "INSERT INTO";
$sql = $sql." commands_given_tbl (command, file_use_id, command_time)";
$sql = $sql." VALUES ('".$command."','".$file_use_key."','".$time."')";

query_db($sql, $debug);

mysql_close(); // on ferme la connexion

debug_msg("Getting the file ");

echo "OK";

?>