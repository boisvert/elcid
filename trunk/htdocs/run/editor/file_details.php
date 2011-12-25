<?php

// definitions of database name, user, password
require('../../cgi/utils.php');

$loggedin=(isset($_COOKIE['user']));

if ($loggedin) {
   // User details
   $username = $_COOKIE['user'];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>

<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>eL-CID :- Edit file details</title>

<?php

   $styleURL=$htURL."images/style.css";
  
   echo '<link rel="StyleSheet" href="'.$styleURL.'" type="text/css">';

?>

</head>

<body>

<?php

  $file_key = $_GET["file"];
  // Make the query

  // select database
  open_db();

  $sql = "SELECT *, count(file_use_key) AS popularity FROM files_tbl";
  $sql=$sql." LEFT JOIN file_uses_tbl ON (files_tbl.file_key = file_uses_tbl.file_id)";
  $sql=$sql." WHERE (file_key=$file_key) AND (file_author='$username') GROUP BY files_tbl.file_key";

  $result = query_db($sql);

  if ($record = mysql_fetch_array($result)) {

	   $sql2 = "select tag_name from tags_tbl";
	   $sql2 = $sql2." INNER JOIN file_tags_tbl ON tags_tbl.tags_key = file_tags_tbl.tag_id";
	   $sql2 = $sql2." WHERE file_id=".$file_key;
	   $tags = query_db($sql2);

       if ($tag = mysql_fetch_array($tags)) {
          $tagcell = $tag["tag_name"];
	      while ($tag = mysql_fetch_array($tags)) {
             $tagcell = $tagcell."; ".$tag["tag_name"];
	      }
       } else {
          $tagcell = "";
	   }

?>
	   <form name="filedetails" target="elcidTutorial" action="file_manager.php"
	         onSubmit="opener.focus();" onCancel="opener.focus();" method="post" >
		  <b>File details</b> <br />
	      <input type="hidden" name="key" value="<?php echo $file_key;?>" />
	      <table align="top">
		    <tr>
			<td>Name:</td> <td><input type="text" name="name" value="<?php echo $record["file_name"];?>" disabled="true" /> </td>
			</tr>
		    <tr>
			<td>Owner:</td> <td><?php echo $record["file_author"];?> </td>
			</tr>
			<tr>
		    <td>Date:</td> <td><input type="text" name="date" value="<?php echo $record["file_date"];?>" /> </td>
			</tr>
			<tr>
		    <td>Description:</td> <td><textarea name="description"><?php echo $record["file_description"];?></textarea> </td>
			</tr>
			<tr>
            <td>Tags:</td> <td><textarea name="tags"><?php echo $tagcell;?></textarea> </td>
			</tr>
			<tr>
		  </table>
          <input type="checkbox" name="public" value="true" <?php echo ($record["file_active"])?"checked":"" ?> /> Publicly available
		  <blockquote>
	        <input type="submit" value="OK" /> <input type="reset" value="Cancel" />
		  </blockquote>
       </form>
<?php
	} // finish showing the files

  mysql_close();  // on ferme la connexion
}
?>

</body>
</html>
