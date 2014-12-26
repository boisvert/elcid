<?php

// Start buffer, sessions, security
ob_start();

if (!isset($_type)) {
   $_type = 'application/xhtml+xml';
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require('security.php');

$loggedin=(isset($_COOKIE['user']));

if ($loggedin) {
   $username = $_COOKIE['user'];
} else {
   $username = "";
}

// Debugging and error reporting

// any errors or debug messages will be logged in this file
// choose your file.
$_log_file = '/error.txt';

if (!isset($debug)) {
   $debug = false;
}

if ($debug) {
   log_new_file();
}

// state variable accumulates info to display in case of PHP errors.
$_state = "";

// debug function: logs the message (in the same file as errors) if either errors are occurring, or if $debug is set.
function debug_msg($message) {
   global $debug;
   global $_state;
   if ($debug) {
      log_msg($message);
   } else {
      $_state = $_state.$message.PHP_EOL;
   }
}

//set error handler
set_error_handler("customError",E_ALL);

//error handler function
function customError($errno, $errstr, $errfile, $errline) {
   bow_out("Error $errno (line $errline): $errstr");
}

// Exit the page if an error occurs
function bow_out($msg) {
   global $_log;
   global $_state;
   global $debug;
   header("HTTP/1.1 500 Internal Server Error"); 
   if (!$debug) {
      log_new_file();
   }
   log_msg($msg);
}

function log_new_file() {
   global $_log_file;
   global $_state;
   global $debug;
   global $_log;
   $_log = fopen($_log_file, "w");
   log_msg();
   log_msg('==========');
   log_msg('Request: '.date("Y-m-d").' '.date("H:i:s"));
   log_msg('File: '.$_SERVER['PHP_SELF']);
   log_msg();
   log_msg($_state);
   $debug = true;
}

// file write + new line

function log_msg($x="") {
  global $_log;
  try{
     fwrite($_log,$x.PHP_EOL);
  } catch(Exception $e) {
      echo "$x<br />";
  }
}

// useful database functions
function open_db() {
   global $server;
   global $user;
   global $pwd;
   global $dbName;
   global $db;
   debug_msg("Connecting to mysql: ".$server." and selecting DB: ".$dbName);
   $db = new mysqli($server, $user, $pwd, $dbName) or  bow_out('No connection: '.$db->error);
}

function close_db() {
   global $db;
   $db -> close();
}

function query_db($sql) {
   global $db;
   debug_msg("Running query: ".$sql);
   $result =  $db -> query($sql) or bow_out('Erreur SQL: '.$db->error);
   return $result;
}

function query_one_row($sql) {
   $result = query_db($sql) -> fetch_array();
   if (!$result) 
   { 
      debug_msg("No result found.");
      return false;
   }
   else
   {
      debug_msg("Result found.");
      return $result;
   }
}

function query_one_item($sql) {
   $result = query_one_row($sql);
   if (!$result) 
   {
      return false;
   }
   else
   {
      return $result[0];
   }
}

// String handling
function stripextension($file_name) {
   $parts = pathinfo($file_name);
   return $parts['filename'];
}

// Clean that magic quotes madness...
function unescape(&$data) {
   if (get_magic_quotes_gpc()) {
      return stripslashes($data);
   }
   else
      return $data;
}

?>