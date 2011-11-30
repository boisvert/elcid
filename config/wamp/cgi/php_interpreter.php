<?php
$debug = false;
session_start();

$elcid_php_code = $_POST['elcid_php_code'];

if ($elcid_php_code=='') {
   debug_msg('No PHP Code found. Recovering session.');
   if (isset($_SESSION['elcid_php_code']))
      $elcid_php_code = $_SESSION['elcid_php_code'];
}
else {
   debug_msg('PHP Code found. Processing '.strlen($elcid_php_code).' characters');
   $elcid_php_code = clean_nbsp($elcid_php_code);

   $php_start = strpos ( $elcid_php_code ,  '<?'  );

   if ($php_start===false)
      $elcid_php_code = echoise( $elcid_php_code );
   else
   {
      debug_msg('PHP tags are present');
      $php_rest = substr( $elcid_php_code, $php_start+2);
      if ( substr_compare($php_rest, 'php', 0, 3) == 0) {
         $php_rest = substr( $php_rest, 3);
      }
      $php_end = strripos ( $php_rest ,  '?>'  );
      $php_mid = slashes_off(substr( $php_rest, 0, $php_end));
      // $host_name, $user_name, $password, $db_name
      $php_mid = str_replace('connect($host_name,$user_name,$password',
	                              'connect("mysql6.000webhost.com","a7847430_test","secr3t"',$php_mid);
      $php_mid = str_replace('select_db($db_name','select_db("a7847430_test"',$php_mid);

      $elcid_php_code = echoise(substr( $elcid_php_code, 0, $php_start)) . $php_mid . echoise(substr( $php_rest, $php_end+2));
   }

   $_SESSION['elcid_php_code']=$elcid_php_code;

}

debug_msg($elcid_php_code);
eval($elcid_php_code);

// clean up spaces before running eval - some space characters break parsing, see
// http://www.justskins.com/forums/38766-new-nonbreaking-whitespace-breaks-parsing-5582.html
// for detail of the problem
function clean_nbsp($string) {
   debug_msg("found nbsps:".substr_count($string, "\xA0"));
   $string = str_replace("\xA0", " ", $string);
   $string = str_replace("\xC2", " ", $string);
   debug_msg("Removed. Found nbsps:".substr_count($string, "\xA0"));
   return $string;
}

function echoise($string) {
   if ($string != '')
     return 'echo(\''.slashes_off($string).'\');';
   else
     return $string;
}

function slashes_off($string) {
   if (get_magic_quotes_gpc()) {
      $string = stripslashes($string);
   }
   return $string;
}

function slashes_on($string) {
   if (!get_magic_quotes_gpc()) {
      $string = addslashes($string);
   }
   return $string;
}

// debug function. Displays the $message if debug is $true
// The $state variable aims at collecting the information for error handling.
function debug_msg($message) {
   global $debug;
   //global $state;
   if ($debug) {
      echo($message."<br>");
   // } else {
      // $state = $state.$message."<br>";
   }
}

// ersatz database function - to open the database without providing all account details to viewers
// deprecated in favour of a token substitution system in the interpreter
function mysql__connect($s, $u, $p) {
   debug_msg("Connecting to mysql: ".$s);
   return mysql_connect("mysql6.000webhost.com","a7847430_test","secr3t");
}
?>
