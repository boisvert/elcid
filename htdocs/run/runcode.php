<?php
$code = $_POST["code"];
$folder = $_POST["folder"];
$i = strpos($code,"</head>");

if ($i>=0)
   $code = substr_replace($code, "<base href='$folder'>", $i, 0); 
else
   $code = "<head><base href='$folder' ></head>$code";

echo $code;
?>
