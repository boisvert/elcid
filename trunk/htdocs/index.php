<?php

// definitions of database name, user, password
require('cgi/utils.php');

$title = "eL-CID :- Web programming, step by step";
$trail = 'You are here: <a href="index.php">Home</a>';
  
$tag = "";

if (isset($_GET["tag"])) {
   $tag = $_GET["tag"];
   $title = "$title - $tag";
   $trail = "$trail &gt; $tag";
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="google-site-verification" content="RLxrL5kZLGnDtFMuWI0mL134ynxrQ21P9r_Z6UQu0Mk">
<title><?php echo $title; ?></title>

<link rel="StyleSheet" href="/images/style.css" type="text/css">

</head>

<body onload="monitor();">

<?php include("images/header.php"); ?>

<div id="trail">
   <?php echo $trail; ?>
</div>

<div style="position:absolute; top:70px; left: 5px; width:280px;">

<h2>Topics</h2>
Choose a tag to find the relevant tutorials.

<div style="width:280; overflow: scroll;" id = "tagCloud">

<?php include("cgi/tag_cloud.php"); ?>

</div>

<h2>What is this?</h2>

<p>
This site is a repository of web programming and web design tutorials.
</p>

<p>
The tutorials all present some code and play through its successive changes.
</p>

<p>
You can also <a href="about">Find out about the system</a>, or <a href="project">visit the project page</a>.
</p>

<p>
Questions, bugs, enthusiastic Oohs and Aahs ;-) all welcome, <a href="contact">contact me</a>.
</p>

</div>

<div id="main">

<h2>Tutorials</h2>
These show the development of HTML/Javascript programs. Select a file to open.

<div style="width:650; height:500; overflow: scroll;" id = "fileList">
<?php include("cgi/file_list.php"); ?>
</div>

</div>

</body>

</html>
