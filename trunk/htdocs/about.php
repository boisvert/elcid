<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>

<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>eL-CID :- about</title>

<link href="css/bootstrap.css" rel="stylesheet">
<link rel="StyleSheet" href="css/style.css" type="text/css">

</head>

<body">

<?php include("header.php"); ?>

<div class="container">

<ol class="breadcrumb">
You are here: 
  <li><a href="index.php">Home</a></li>
  <li class="active">About</li>
</ol>

<div class="col-md-4" style="background:#00AF64; color:#FFFFFF; border-radius: 5px;">

   <h2>Table of Contents</h2>

   <div class="list-group">
<a href="#What is eL-CID?" class="list-group-item">What is eL-CID?</a>
<a href="#How does it work?" class="list-group-item">How does it work?</a>
<a href="#I'd like to make tutorials" class="list-group-item">I'd like to make tutorials</a>
<a href="#It doesn't work in my browser" class="list-group-item">It doesn't work in my browser</a>
<a href="#Can I change the system or make my own version?" class="list-group-item">Can I change the system or make my own version?</a>
   </div>

</div>

<div class="col-md-8" style="overflow:scroll; max-height: 500px;">

<div id="What is eL-CID?">

<h3>What is eL-CID?</h3>

<p>
eL-CID was written to help learn to develop programs by viewing examples of <i>iterative development</i>.
Iterative development means writing simple programs then improving them step by step.
It is a very common technique, but because of its progressive nature, teachers find difficult to demonstrate it on paper.
</p>

<p>
eL-CID stands for <i>e-Learning by Communicating Iterative Development</i>.
</p>

</div>

<div id="How does it work?">

<h3>How does it work?</h3>

<p>
eL-CID takes a program and improvements that a good programmer has written.
Then it displays the initial listing, and shows the step by step changes as if the program was being edited in front of the user.
At each step the user can stop to observe the changes and run the work in progress.
</p>

<p>
Data about each program and its improvements is stored in XML format.
When eL-CID opens an XML data file, it analyses the data to prepare the demonstration.
It then shows the program and iterative modifications.
</p>

<p>
The possible program changes are <i>cursor move</i>, <i>insert</i>, <i>select</i>, <i>delete</i>, <i>copy</i>, and <i>paste</i>.
This is enough to cover all the possible changes made to a text file.
At the start, I wrote it because I am tired of giving students masses of handouts that show successive versions of the same program.
</p>

</div>

<div id="I'd like to make tutorials">

<h3>I'd like to make tutorials</h3>

<p>
You should register online. The editor is not easy to use yet. Use the tutorials included as guidelines to the XML format used.
</p>

<p>
The system is free under the GNU Public License, but you retain the copyright to your tutorials - the boring details are in the download.
</p>

</div>

<div id="It doesn't work in my browser">

<h3>It doesn't work in my browser</h3>

<p>
eL-CID has been tried on Safari, Firefox, Internet Explorer, Opera, and Chrome.
Javascript should be enabled and you need a browser that supports AJAX.
If your browser fulfills these conditions, and it still doesn't work, let me know.
</p>

<p>
<a href="contact.php">Let me know</a> if you find bugs.
</p>

</div>

<div id="Can I change the system or make my own version?">

<h3>Can I change the system or make my own version?</h3>

<p>
The source is on Google code, <a href="project.php">download it</a>. Contributions are welcome, so is forking.
</p>

</div>

</div>

</div>

<?php include("footer.php"); ?>

</body>
</html>
