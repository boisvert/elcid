<?php
require_once("../cgi/utils.php");

$code = unescape($_POST['code']);
$fdir = $htURL."run/phpbin/";

if ($code=='') {
   debug_msg('No PHP Code found. Recovering session.');
   if (isset($_SESSION['file'])) {
      $fname = $_SESSION['file']; 
      // file is already saved.
   }
   else {
      echo "No file found.";
   }
}
else {
   debug_msg('PHP Code found. Processing '.strlen($code).' characters');
   if ($_SERVER['HTTP_REFERER'] == $htURL."run/runphpfiddle.html") {
      debug_msg('Referrer correct. making a filename.');
      $fileUseID = isset($_POST["fileUseID"])?$_POST["fileUseID"]:'000';
      $fname = date('YmdHis').$fileUseID;
      debug_msg('File name:'.$fname);
      save_file($code,$fname);
      $_SESSION['file']=$fname;
   }
   else {
      echo "Sorry, we cannot allow cross-site scripting.<br />";
      echo "If you wish to use a PHP remote execution tool, consider PHPfiddle, Google appEngine or a free PHP host such as 000webhost.";
   }   
}

if (isset($fname)) {
   // create a URL to use for PHPFiddle
   $furl = $htURL."run/phpbin/".$fname;
   debug_msg('PHP URL is available:'.$furl);
   // and send to run
   debug_msg('Results go here.');
   echo fiddle_exec($furl);
}
else {
   debug_msg('No PHP Code to execute.');
}

// save file function.
// alternatively, could the files be saved on the cloud, e.g. dropbox?
function save_file(&$code,$fname) {
   $fh = fopen('phpbin/'.$fname, 'w') or die("can't open file");
   fwrite($fh, $code);
   fclose($fh);
   debug_msg("Save succeeded");
}

function fiddle_exec($furl) {
   $url = 'http://phpfiddle.org/api/run/url';
   $data = $_POST;
   $data['url'] = $furl;

   // use key 'http' even if you send the request to https://...
   $options = array(
      'http' => array(
         'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
         'method'  => 'POST',
         'content' => http_build_query($data),
      ),
   );
   $context  = stream_context_create($options);
   $result = file_get_contents($url, false, $context);

   return get_string_between($result,'<pre>','</pre>');
}

// from PHP manual forum discussion
function get_string_between(&$string, $start, $end){
   $ini = strpos($string,$start);
   $ini += strlen($start);    
   $len = strrpos($string,$end,$ini) - $ini;
   return substr($string,$ini,$len);
}

?>
