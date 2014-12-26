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
   $styleURL=$htURL."css/style.css";
   echo '<link rel="StyleSheet" href="'.$styleURL.'" type="text/css">';
?>

</head>

<body>

<?php

   $file_id = $_GET["file"];
   // Make the query

   // select database
   open_db();

   $sql = "SELECT *, count(load_id) AS popularity FROM file";
   $sql .= " LEFT JOIN file_use ON (file.file_id = file_use.file_id)";
   $sql .= " WHERE (file.file_id=$file_id) AND (file_author='$username') GROUP BY file.file_id";

   $result = query_db($sql);

   if ($record = mysqli_fetch_array($result)) {

	   $sql2 = "select tag from file_tag WHERE file_id=".$file_id;
	   $tags = query_db($sql2);

      if ($tag = mysqli_fetch_array($tags)) {
         $tagcell = $tag["tag"];
	      while ($tag = mysqli_fetch_array($tags)) {
            $tagcell = $tagcell."; ".$tag["tag"];
	      }
      } else {
         $tagcell = "";
	   }

?>
	   <form name="filedetails" action="file_manager.php"
	         onSubmit="opener.focus();" onCancel="opener.focus();" method="post" >
		  <b>File details</b> <br />
	      <input type="hidden" name="key" value="<?php echo $file_id;?>" />
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

  close_db();  // on ferme la connexion
}
?>

</body>
</html>
