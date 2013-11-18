<?php

  // definitions of database name, user, password
  require_once('utils.php');
  
?>

<!--
eL-CID, Copyright (C) Anglia Polytechnic University, 2003-04, Charles Boisvert, 2004-05.

eL-CID is free software, and you are welcome to redistribute
it under certain conditions; it comes with ABSOLUTELY NO
WARRANTY; for details read the General Public Licence included
with the distribution.
-->

<html>

<head>

   <title>eL-CID: e-Learning by Communicating Iterative Development</title>
   <link rel="stylesheet" type="text/css" href="images/code.css">
   <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
   
   <style>
      a {text-decoration:underline; color:blue;}
   </style>

<script type="text/javascript">

function runTutorial(tutorial)  {
   document.filename.file.value=tutorial;
}

</script>
   
</head>

<body>

<form name="filename">
   Select a file:<br />
   <input type="text" name="file" size="40">
   <p>
      <input type="button" value="Load file"
          onClick="opener.loadCommandServer(document.filename.file.value); opener.focus();">
      <input type="button" value="Cancel" onClick="opener.focus();">
   </p>
</form>


<div style="width:650; height:500; overflow: scroll;" id = "fileList">
<?php include("../cgi/file_list.php"); ?>
</div>


</body>

</html>
