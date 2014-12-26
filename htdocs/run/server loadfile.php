<?php

  // definitions of database name, user, password
  require_once('../cgi/utils.php');
  
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

function runTutorial(fileName) {
  opener.location = htURL+"run/elcid.html?file="+encodeURI(fileName);
  opener.focus();
}

</script>
   
</head>

<body>

<?php

$tag = "";
if (isset($_GET["tag"])) {
   $tag = $_GET["tag"];
}

include("../cgi/file_list.php");

?>

</body>

</html>
