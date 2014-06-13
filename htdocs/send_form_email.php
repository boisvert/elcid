<?php
if(isset($_POST['email'])) {
     
   // EDIT THE 2 LINES BELOW AS REQUIRED
   $email_to = "charles@boisvert.me.uk";
   $email_subject = "eL-CID Inquiry";
     
     
   function died($error) {
      // your error code can go here
      echo "We are very sorry, but there were error(s) found with the form you submitted. ";
      die();
   }
     
   // validation expected data exists
   if(!isset($_POST['name']) ||
      !isset($_POST['comments'])) {
      died('We are sorry, but there appears to be a problem with the form you submitted.');       
   }

   $name = $_POST['name']; // required
   $email_from = $_POST['email']; // required
   $comments = $_POST['comments']; // required

   $error_message = "";
   $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
   if(!preg_match($email_exp,$email_from)) {
      $error_message .= 'The Email Address you entered does not appear to be valid.<br />';
   }
   
   if(strlen($comments) < 2) {
     $error_message .= 'The Comments you entered do not appear to be valid.<br />';
   }
   
   if(strlen($error_message) > 0) {
      died($error_message);
   }
   $email_message = "Form details below.\n\n";
     
   function clean_string($string) {
      $bad = array("content-type","bcc:","to:","cc:","href");
      return str_replace($bad,"",$string);
   }

   $email_message .= "Name: ".clean_string($name)."\n";
   $email_message .= "Subject: ".clean_string($last_name)."\n";
   $email_message .= "Email: ".clean_string($email_from)."\n";
   $email_message .= "Telephone: ".clean_string($telephone)."\n";
   $email_message .= "Inquiry: ".clean_string($comments)."\n";
     

   // create email headers
   $headers = 'From: '.$email_from."\r\n".
   'Reply-To: '.$email_from."\r\n" .
   'X-Mailer: PHP/' . phpversion();
   @mail($email_to, $email_subject, $email_message, $headers);  
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>

<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>eL-CID :- contact</title>

<link href="css/bootstrap.css" rel="stylesheet">
<link rel="StyleSheet" href="css/style.css" type="text/css">

</head>

<body onload="monitor();">

<?php include("header.php"); ?>

<div class="container">

<ol class="breadcrumb">
You are here: 
  <li><a href="index.php">Home</a></li>
  <li><a href="contact.php">Contact</a></li>
  <li class="active">Thank you</li>
</ol>

Thank you for contacting me. I will be in touch with you very soon.


</div>

<?php include("footer.php"); ?>

<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>

<?php
}
?>