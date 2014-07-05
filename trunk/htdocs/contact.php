<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>

<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>eL-CID :- contact</title>

<link rel="StyleSheet" href="css/style.css" type="text/css">
<link href="css/bootstrap.css" rel="stylesheet">

</head>

<body onload="monitor();">

<?php include("header.php"); ?>

<div class="container">

<ol class="breadcrumb">
You are here: 
  <li><a href="index.php">Home</a></li>
  <li class="active">Contact</li>
</ol>

<div class="col-md-4">

<h3>Contact Info</h3>

<p>
Charles Boisvert,<br>
Department of Computing,<br>
Sheffield Hallam University,<br>
Howard Street,<br>
Sheffield S1 1WB,<br>
United Kingdom
</p>

</div>

<div class="col-md-8">

<h3>Send me a Message</h3>

<form name="contactform" method="post" action="send_form_email.php">

<div class="input-group">
  <span class="input-group-addon">Name :</span>
  <input type="text" class="form-control" placeholder="Enter your name..." name="name">
</div>
<br />
<div class="input-group">
  <span class="input-group-addon">Email :</span>
  <input type="text" class="form-control" placeholder="Enter email..." name="email">
</div>
<br />
<div class="input-group">
  <span class="input-group-addon">Message :</span>
  <textarea maxlength="1000" cols="25" rows="6" class="form-control" placeholder="Enter message..." name="comments"></textarea>
</div>
<br /> 
  <input type="submit" value="Submit" class="btn btn-default">  
</form>

</div>

</div>

<?php include("footer.php"); ?>

<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>

</html>