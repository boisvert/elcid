<?php

// debug mode. Uncomment to get debug messages on screen.
$debug = true;

// database and debug utilities
require_once('utils.php');

session_start();

$date = date("Y-m-d");
$time = date("H:i:s");

// select database  
open_db();

$session_id = session_id();

// Is there a session in the DB with this key?
debug_msg("Looking for session (No. ".$session_id.")");
$sql = "SELECT 1 FROM session WHERE session_id='$session_id'";

// If not, record it now
if (!query_one_item($sql)) {
   debug_msg("Session not yet recorded. Recording now.");
   $client_ip = $_SERVER['REMOTE_ADDR'];
   $remote_host = $_SERVER['REMOTE_HOST'];
   $client_desc = $_SERVER['HTTP_USER_AGENT'];

   $sql = "INSERT INTO";
   $sql .= " session (client_description, client_ip, client_host_name, session_date, session_time, session_id)";
   $sql .= " VALUES ('$client_desc','$client_ip','$remote_host','$date','$time','$session_id')";

   query_db($sql);

} // finish recording the new session

$fileuseid = $_GET['fileuseid'];

$referrer = '';
if (isset($_SERVER['HTTP_REFERER'])) {
   $referrer = $_SERVER['HTTP_REFERER'];
}

$qs = $_SERVER["QUERY_STRING"];
  
$sql = "INSERT INTO";
$sql .= " session (load_id, session_id, date, time, url, query_string, referrer)";
$sql .= " VALUES ('$fileuseid','$session_id','$date','$time','$qs','$referrer')";

query_db($sql);



close_db(); // on ferme la connexion

?>
