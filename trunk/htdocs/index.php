<?php

$title = "eL-CID :- Learn programming, step by step";
  
$tag = "";

if (isset($_GET["tag"])) {
   $tag = $_GET["tag"];
   $title = "$title - $tag";
   $trail = "<li><a href='index.php'>Home</a></li> <li class='active'>$tag</li>";
} else {
   $trail = "<li class='active'>Home</li>";
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="google-site-verification" content="RLxrL5kZLGnDtFMuWI0mL134ynxrQ21P9r_Z6UQu0Mk">
<title><?php echo $title; ?></title>

<link href="css/bootstrap.css" rel="stylesheet">
<link rel="StyleSheet" href="css/style.css" type="text/css">

</head>

<body onload="monitor();">

<?php include("header.php"); ?>

<div class="container">

<ol class="breadcrumb">
You are here: 
  <?php echo $trail; ?>
</ol>

<div class="col-md-4" style="background:#00AF64; color:#FFFFFF; border-radius: 5px;">

<h2>Choose:</h2>

<div style="overflow: scroll;" id = "tagCloud">
  <?php include("cgi/tag_cloud.php"); ?>
</div>

<h2>Learn to program</h2>

<p>
This site is a repository of web programming and web design tutorials.
</p>

<p>
The tutorials all present some code and play through its successive changes.
</p>

<p>
You can also <a href="about.php" style="color:#DAE9FF;">Find out about the system</a>, or <a href="project.php" style="color:#DAE9FF;">visit the project page</a>.
</p>

<p>
Questions, bugs, enthusiastic Oohs and Aahs ;-) all welcome, <a href="contact.php" style="color:#DAE9FF;">contact me</a>.
</p>

</div>

<div class="col-md-8">
   <?php include("cgi/file_list.php"); ?>
</div>

</div>

<?php include("footer.php"); ?>

</body>

</html>
