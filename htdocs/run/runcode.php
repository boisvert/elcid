<?php
$code = unescape($_POST["code"]);


$folder = unescape($_POST["folder"]);
$i = strpos($code,"</head>");

if ($i>=0)
   $code = substr_replace($code, "<base href='$folder'>", $i, 0); 
else
   $code = "<head><base href='$folder' ></head>$code";

echo $code;

// Clean that magic quotes madness...
function unescape(&$data) {
   if (get_magic_quotes_gpc()) {
      return stripslashes($data);
   }
   else
      return $data;
}

?>
