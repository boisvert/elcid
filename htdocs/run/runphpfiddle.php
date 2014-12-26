<?php
// uncomment to display debug info
//$debug = true;

require_once("../cgi/utils.php");

$code = unescape($_POST['code']);

if ($code=='') {
   debug_msg('No PHP Code found. Recovering session.');
   if (!isset($_SESSION['file'])) bow_out("Nothing to execute.");
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
   // remote save is sufficient
   $userEdited = unescape($_POST['userEdited']);
   debug_msg("User edited file: $userEdited");
   if ($userEdited=='yes') {
      debug_msg('Saving for monitoring.');
      save_file($code,$fname);
   }
   $_SESSION['file']=$fname;
   remote_save($code,$fname);
}

if (!isset($fname)) bow_out('Nothing to execute.');

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
   // $fdir = $htURL."run/phpbin/";
   $fh = fopen('phpbin/'.$fmonitorname, 'w') or bow_out("can't open file");
   fwrite($fh, $code);
   fclose($fh);
   debug_msg("Save succeeded");
}

function remote_save(&$code,$fname) {
   global $remotePHP;
   $url = $remotePHP."/save.php";
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
   debug_msg("Saving $url");
   $result = file_get_contents($url, false, $context);

   return $result;
}

function fiddle_exec($fname) {
   global $remotePHP;
   $url = $remotePHP."/go.php";
   if ($_SERVER["QUERY_STRING"]) {
      $url .= $url.'?'.$_SERVER["QUERY_STRING"];
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
   debug_msg("Executing $url");
   $result = file_get_contents($url, false, $context);

   return $result;
}

function safe_referrer($file, $msg) {
   global $htURL;
   if ($_SERVER['HTTP_REFERER'] != $htURL.$file) {
      echo "Not allowed.<br />";
      bow_out($msg);
   }
   debug_msg('Referrer correct.');
}

?>
