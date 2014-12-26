<?php

// debug mode. Uncomment to get debug messages on screen.
//$debug = true;

// set mime type
// also used for testing to inject some js, so the header is not enough
$_type = 'application/javascript';

// database and debug functionality
require_once('utils.php');

$date = date("Y-m-d");
$time = date("H:i:s");

$session_id = session_id();

// Is there a session in the DB with this key?
debug_msg("Looking for session (No. ".$session_id.")");

$editor = 0;
if (isset($_GET["editor"])) {
   if ($_GET["editor"]=='on'||$_GET["editor"]=='yes'||$_GET["editor"]=='true'||$_GET["editor"]=='1')
      $editor = 1;
}

debug_msg("Editor: ".$editor);

$file_name = isset($_GET["fname"])?$_GET["fname"]:"";
$file_path = isset($_GET["fpath"])?substr($_GET["fpath"], 0, -1):"";

debug_msg("Looking for file (name: ".$file_name."; path: ".$file_path.")");

// select database  
open_db();

// (1) Check if the file is known in the DB, if not, record it 
// Is there a file in the DB with this name & path?
$sql = "SELECT file_id FROM file WHERE file_name='$file_name' AND file_path='$file_path' ";

// Get file_id. 
if (!($file_id = query_one_item($sql)))
{ // There is no file with that path/name. Insert one.
   debug_msg("File not yet recorded. Recording now.");
   $sql = "INSERT INTO";
   $sql .= " file (file_name, file_path, file_active)";
   $sql .= " VALUES ('$file_name','$file_path', 0)";

   query_db($sql);

   $file_id = query_one_item("SELECT MAX(file_id) FROM file");
} // finish inserting the file

if (isset($_GET["loadid"])) { // there an existing page_load
   $loadid = $_GET["loadid"];
   debug_msg("Load ID exists, no. $loadid");
}
else { // there is no page load data
   debug_msg("Recording page load");

   if ($loadid = query_one_item("SELECT max(load_id)+1 FROM page_load WHERE session_id='$session_id'")) {
      // there is a session
      debug_msg("Session found, loadid will be $loadid");
   }
   else {
      // there is no session,
      // record a new one
      debug_msg("Session not yet recorded. Recording now. Loadid is 0");
      $client_ip = $_SERVER['REMOTE_ADDR'];
      $remote_host = $_SERVER['REMOTE_HOST'];
      $client_desc = $_SERVER['HTTP_USER_AGENT'];

      $sql = "INSERT INTO";
      $sql .= " session (client_description, client_ip, client_host_name, session_date, session_time, session_id)";
      $sql .= " VALUES ('$client_desc','$client_ip','$remote_host','$date','$time','$session_id')";

      query_db($sql);
      
      $loadid = 0;
   } // finish inserting the new session
   
   // insert the page load and file use.

   $referer = '';
   if (isset($_SERVER['HTTP_REFERER'])) {
      $referer = $_SERVER['HTTP_REFERER'];
   }

   $uri = '';
   if (isset($_SERVER['REQUEST_URI'])) {
      $uri = strtok($_SERVER["REQUEST_URI"],'?');
   }

   $qs = '';
   if (isset($_SERVER['QUERY_STRING'])) {
      $qs = $_SERVER['QUERY_STRING'];
   }

   debug_msg("Recording the page load $loadid for this session.");

   $sql = "INSERT INTO";
   $sql .= " page_load (load_id, session_id, date, time, url, query_string, referer)";
   $sql .= " VALUES ($loadid, '$session_id', '$date', '$time', '$uri', '$qs', '$referer')";

   query_db($sql);
   
   // Now record the file_use for this session

   debug_msg("Recording the file use.");

   $sql = "INSERT INTO";
   $sql .= " file_use (session_id, load_id, file_id, file_use_edit)";
   $sql .= " VALUES ('$session_id', $loadid, $file_id, $editor)";

   query_db($sql);
} // finish recording the page load data

// record the command
$command = $_GET["command"];

debug_msg("Recording the user command: ".$command);

$sql = "INSERT INTO";
$sql .= " command (session_id, load_id, command_time, command)";
$sql .= " VALUES ('$session_id', $loadid, '$time', '$command')";

query_db($sql, $debug);

close_db(); // on ferme la connexion

echo $loadid;

?>

