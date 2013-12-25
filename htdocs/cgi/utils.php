<?php

if (!isset($debug)) {
   $debug = false;
}

session_start();

require('security.php');

$loggedin=(isset($_COOKIE['user']));

if ($loggedin) {
   $username = $_COOKIE['user'];
} else {
   $username = "";
}

// state variable accumulates info to display in case of PHP errors.
$_state = "";

// useful database functions
function open_db() {
   global $server;
   global $user;
   global $pwd;
   global $dbName;
   global $_state;
   debug_msg("Connecting to mysql: ".$server);
   $db = mysql_connect($server, $user, $pwd) or die($_state.'Erreur de connexion '.mysql_error());
   debug_msg("Selecting DB: ".$dbName);
   mysql_select_db($dbName,$db) or die($_state.'Erreur de selection '.mysql_error());
}

function close_db() {
   mysql_close();
}

function query_db($sql) {
   global $_state;
   debug_msg("Running query: ".$sql);
   $result = mysql_query($sql) or die($_state.'Erreur SQL !<br>'.mysql_error());
   return $result;
}

function query_one_row($sql) {
   $result = mysql_fetch_row(query_db($sql));
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

// debug function.
function debug_msg($message) {
   global $debug;
   global $_state;
   if ($debug) {
      echo($message."<br>");
   } else {
      $_state = $_state.$message."<br>";
   }
}

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