<?php

// debug mode. Uncomment to get debug messages on screen.
//$debug = true;

// database and debug utilities
include('cgi/utils.php');

$date = date("Y-m-d");
$time = date("H:i:s");

$session_id = session_id();

// Is there a session in the DB with this key?
debug_msg("Looking for session (No. ".$session_id.")");

// select database  
open_db();

if ($loadid = query_one_item("SELECT max(load_id)+1 FROM page_load WHERE session_id='$session_id'")) {
}
else {
   debug_msg("Session not yet recorded. Recording now.");
   $client_ip = $_SERVER['REMOTE_ADDR'];
   $remote_host = $_SERVER['REMOTE_HOST'];
   $client_desc = $_SERVER['HTTP_USER_AGENT'];

   $sql = "INSERT INTO";
   $sql .= " session (client_description, client_ip, client_host_name, session_date, session_time, session_id)";
   $sql .= " VALUES ('$client_desc','$client_ip','$remote_host','$date','$time','$session_id')";

   query_db($sql);
   
   $loadid = 0;
} // finish recording the new session

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

debug_msg("Recording the page load.");

$sql = "INSERT INTO";
$sql .= " page_load (load_id, session_id, date, time, url, query_string, referer)";
$sql .= " VALUES ($loadid,'$session_id','$date','$time','$uri', '$qs','$referer')";

query_db($sql);

close_db(); // on ferme la connexion

?>

<script type="text/javascript" src="images/behaviour.js"> </script>

<script LANGUAGE="JavaScript" src="images/loggedin.php" type="text/javascript"> </script>

<div class="container">

   <div class="col-md-3">
      <a href="index.php"><img src="images/logo.jpg" height="100" class="img-responsive" /></a>
   </div>
   
   <div class="col-md-7" style="background:#00AF64; color:#FFFFFF; border-radius: 5px;">

      <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
         <!-- Indicators -->
         <ol class="carousel-indicators">
            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
            <li data-target="#carousel-example-generic" data-slide-to="2"></li>
         </ol>

         <!-- Wrapper for slides -->
         <div class="carousel-inner">
            <div class="item active">
               <img src="images/1.jpg" alt="...">
            </div>
            <div class="item">
               <img src="images/2.jpg" alt="...">
            </div>
            <div class="item">
               <img src="images/3.jpg" alt="...">
            </div>
         </div>

         <!-- Controls
         <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
         </a>
         <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
         </a>
         -->
      </div>
   </div>
</div>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="navbar-inner">
      <div class="container">
         <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
               <span class="sr-only">Toggle navigation</span>
               <span class="icon-bar"></span>
               <span class="icon-bar"></span>
               <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">Home</a>
         </div>
         <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
               <li><a href="about.php">About eL-CID</a></li>            
               <li><a href="project.php">Project</a></li>
               <li><a href="contact.php">Contact</a></li>
               <!-- li><a href="search.php">Search</a></li -->
            </ul>
            <ul id="login" class="nav navbar-nav navbar-right"></ul>          
         </div> <!--/.nav-collapse -->
      </div>
   </div>
</div>

<script type="text/javascript">
   welcomeLogin();
</script>


