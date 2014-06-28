<?php
require_once("../cgi/utils.php");

// uncomment to display debug info
$debug = true;

$code = unescape($_POST['code']);
$fdir = $htURL."run/phpbin/";

if ($code=='') {
   debug_msg('No PHP Code found. Recovering session.');
   if (!isset($_SESSION['file'])) die("Nothing to execute.");
   // file is already saved.
   $fname = $_SESSION['file'];
}
else {
   // there is new code
   debug_msg('PHP Code found. Processing '.strlen($code).' characters');
   safe_referrer("run/runphpfiddle.html", "If you wish to use a PHP remote execution tool, consider PHPfiddle, Google appEngine or a free PHP host such as 000webhost.");
   debug_msg('Making a file.');
   $fname = session_id();
   // todo: if the file has not been manually changed by the user,
   // there is no need for local save, as local save is for security monitoring purposes.
   // remote save would be sufficient
   save_file($code,$fname);
   $_SESSION['file']=$fname;
   remote_save($code,$fname);
}

if (!isset($fname)) die('Nothing to execute.');

// create a URL to use for PHPFiddle
debug_msg('PHP file is available:'.$fname);
debug_msg('Execution results:');
// and send to run
echo fiddle_exec($fname);

// save file function.
// alternatively, could the files be saved on the cloud, e.g. dropbox?
function save_file(&$code,$fname) {
   // name of file is sessionID-YYMMDDHHMMSS
   // eventually should be author-filename/sessionID-YYMMDDHHMMSS
   // to allow easy safety monitoring
   $fmonitorname = $fname.date('-YmdHis');
   debug_msg('File name:'.$fmonitorname);
   $fh = fopen('phpbin/'.$fmonitorname, 'w') or die("can't open file");
   fwrite($fh, $code);
   fclose($fh);
   debug_msg("Save succeeded");
}

function remote_save(&$code,$fname) {
   // name of remote file is sessionID
   // no other data needed: on the contrary, old unused files should be overwritten
   $data = array(
      'fname' => $fname,
      'code' => $code,
   );
   // use key 'http' even if you send the request to https://...
   $options = array(
      'http' => array(
         'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
         'method'  => 'POST',
         'content' => http_build_query($data),
      ),
   );
   $context  = stream_context_create($options);
   $result = file_get_contents($remotePHP, false, $context);

   return $result;
}

function fiddle_exec($fname) {
  if ($_SERVER["QUERY_STRING"]) {
      $url .= $remotePHP.'?'.$_SERVER["QUERY_STRING"];
   }
   $data = $_POST;
   $data['fname'] = $fname;

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

   return $result;
}

function safe_referrer($file, $msg) {
   debug_msg($_SERVER['HTTP_REFERER']);
   debug_msg($htURL.$file);
   if ($_SERVER['HTTP_REFERER'] != $htURL.$file) {
      echo "Not allowed.<br />";
      die($msg);
   }
   debug_msg('Referrer correct.');
}

?>
