<?php
// debug mode, uncomment to use
// $debug=true;

// login, username
require('../../cgi/login.php');

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>

<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>eL-CID :- file manager</title>

<?php // base and style sheet

$styleURL=$htURL."css/style.css";

echo '<link rel="StyleSheet" href="'.$styleURL.'" type="text/css">';

?>

<style>
.file {}
.hifile {
   background-color: yellow;
}

table.filemanager tr {
   color:#000000;
   background-color: #cccc99;
   margin: 1px 1px 1px 1px;
}

</style>

<script>

function hifile(elt) {
   elt.className='hifile';
}

function lofile(elt) {
   elt.className='file';
}

function togglePublic(fileKey, public) {
   var parameters = "file="+fileKey+"&public="+public;
   // alert(parameters);
   parent.POSTRequest(cgiURL+'set_public.php', parameters, setPublicResponse);
}

function setPublicResponse(req) {
   // alert("Request readyState: " + parent.httpRequest.readyState);
   if (req.readyState == 4) {
      // alert("Response status: " + parent.httpRequest.status);
      if (req.status == 200) {
         window.location=window.location+" ";
 alert(req.responseText);
      }
      else {
 msg = 'There was a problem changing the file status.\nCheck that you are logged in\n';
         alert(msg+req.responseText);
     }
   }
}

function detailsDialog(fileKey) {
   var winloc="file_details.php?file="+fileKey;
   var winprop = 'height=500,width=450,location=no,scrollbars=no,menu=no,toolbar=no,status=no,resizable=yes';
   var w = window.open(winloc, 'file', winprop);
   w.focus();
}

</script>

</head>

<body>

   <?php 
   
   // Find the sort order
   if (isset($_GET["msg"]))
      echo $_GET["msg"]."<br/>";

   if (isset($_GET["order"]))
      $order = $_GET["order"];
   else
      $order = "visits";

   if (isset($_GET["dir"]))
      $dir = $_GET["dir"];
   else
      $dir = "desc";
   
   // Column header with sort
   function sort_header($column) {
      global $order;
      global $dir;
      global $cgiURL;
      $q = "<a href='?order=$column&dir=";
      if ($column == $order) {
         if ($dir=="asc")
            $q .= "desc";
         else
            $q .= "asc";
      }
      else {
         $q .= "asc";
      }
      $q .= "'>$column</a>";
      return $q;      
   }
   
   // Shows a link to recover file if a copy of the file exists in the ./temp directory
   function recovery($file,$path) {
      $url = $path."/temp/$file.xml";
      debug_msg("Looking for file");
      debug_msg($url);
      if (file_exists($url)) {
         debug_msg("Found");
         $js = 'opener.loadCommandServer("'.$url.'"); return false;';
         ?>
         Recover:
         <a href='<?php echo $url;?>' onClick='<?php echo $js;?>'>
      <?php echo $file;?>
   </a>
         <?php
      }
      else {
         debug_msg("No file to recover");
      }
   }
   ?>

   <!-- file list -->
   <table class='filemanager' cellspacing='1' cellpadding='1'>

      <tr>
   <td><?php echo sort_header('active');?></td>
   <td><?php echo sort_header('name');?></td>
   <td><?php echo sort_header('date');?></td>
   <td><?php echo sort_header('visits');?></td>
   <td>Details</td>
         <td bgcolor="ffffff"> <!--recovery column--> </td>
      </tr>

<?php

// connect to database
open_db();

// optionally update file details
if (isset($_POST["key"]) && isset($_POST["date"])) {

   // Details to update
   
   $d = $_POST["date"];
   $sets = "file_date='$d'";
   
   if (isset($_POST["public"])) {
$sets .= ", file_active=1";
   }
   else {
$sets .= ", file_active=0";
   }
   // also need file_name='$_POST["name"]',
   // but not implemented because needs renaming the file itself

   if (isset($_POST["description"])) {
      $d = $_POST["description"];
$sets .= ", file_description='$d'";
   }

   $key=$_POST["key"];
 
   $sql = "UPDATE file SET $sets WHERE file_id=$key AND file_author='$username';";
   query_db($sql);

   // update file classification tags
   $tags = $_POST["tags"];
   debug_msg("Tags:".$tags);

   // Remove old tags
   $sql = "DELETE FROM file_tag WHERE (file_id=$key)";
   query_db($sql);

   // Put in the new tags
   $tagsArray = explode(";",$tags);

   foreach ($tagsArray as $tag) {
      $tag=trim($tag);
      if ($tag!="")
      {
         $sql = "INSERT INTO tag(tag,tag_creation_date,tag_author) VALUES ('$tag',now(),'$username')";

         query_db($sql);

         $sql = "INSERT INTO file_tag VALUES ($key,'$tag','$username')";
         query_db($sql);
} // one tag
   } // each tag
} // File updated if needed

// Query the file list
$sql  = "SELECT file.file_id, file_active AS active, file_name AS name, file_date AS date, file_path AS path, count(load_id) AS visits FROM file";
$sql .= " LEFT JOIN file_use ON (file.file_id = file_use.file_id)";
$sql .= " WHERE (file_author='$username')";
$sql .= " GROUP BY file.file_id ORDER BY $order $dir";

$total=0;
$result = query_db($sql);

while ($record = mysqli_fetch_array($result)) {
   $file_id = $record["file_id"];
   $file = $record["name"];
   $path = "../".$record["path"];
   $url = "$path/$file.xml";
   $js = 'opener.loadCommandServer("'.$url.'"); return false;';
?>
      <tr onMouseover="hifile(this);" onMouseout="lofile(this);" />
<td><form>
   <input type="checkbox" onChange="togglePublic(this.id, this.checked);"
          id="<?php echo $file_id;?>" <?php echo ($record["active"])?"checked":"" ?> / >
</form></td>
<td>
         <a href='<?php echo $url;?>' onClick='<?php echo $js;?>'>
      <?php echo $file;?>
   </a>
</td>
<td>
   <?php echo $record["date"];?>
</td>
<td>
   <?php echo $record["visits"];?>
</td>
<td>
         <img src="../../images/pencil.png" alt="Edit details"
        id="<?php echo $file_id;?>" onClick="detailsDialog(this.id);" / >
      </td>
      <td bgcolor="ffffff">
         <?php recovery($file,$path); ?>
      </td>
      </tr>
<?php
   $total++;
} // finish showing the files

close_db();  // close connection
?>
   </table>

   <?php echo $total; ?> files

   <p>Upload:</p>

   <form action="upload_file.php" method="post" enctype="multipart/form-data">
      <input type="file" name="tutorial" onChange="this.form.submit();">
   </form>

</body>
</html>
