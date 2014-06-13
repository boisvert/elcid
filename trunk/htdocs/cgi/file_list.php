<?php

  // definitions of database name, user, password
  require_once('utils.php');
  
?>

<script type="text/javascript">

var tagsRequest = false;

function hitags(elt) {
 if (parent.loggedin) elt.className='hitags';
}

function lotags(elt) {
 if (parent.loggedin) elt.className='tags';
}

function editTags(elt) {
   if (parent.loggedin) {
      var tags = elt.innerHTML;
      if (tags.indexOf(';')==-1) // if no tags
	     tags = '';
	  newTags = prompt("Edit the tags:", tags);
	  if (newTags!=null && newTags!=tags) setTags(elt.id, newTags);
   }
   else {
      alert("Login or register to edit the tags.");
   }
}

function setTags(fileId, tags) {
   var fileKey = fileId.substring(1);
   var parameters = "file="+fileKey+"&tags="+tags;
   tagsRequest = parent.POSTRequest('cgi/set_tags.php', parameters, setTagsResponse);
}

function setTagsResponse() {
   if (tagsRequest.readyState == 4) {
      if (tagsRequest.status == 200) {
         window.location=window.location+" ";
      }
      else
         alert('There was a problem settings the tags.\n'+tagsRequest.responseText);
   }
}

</script>

<?php

   // select database  
   open_db();

   // Make the query
   $sql  = "SELECT files_tbl.*, count(file_use_key) AS popularity, avg(rate) as stars FROM files_tbl";
   $sql .= " LEFT JOIN file_uses_tbl ON (files_tbl.file_key = file_uses_tbl.file_id)";
   $sql .= " LEFT JOIN file_rating_tbl ON (files_tbl.file_key = file_rating_tbl.file_id)";

   if ($tag == "")
      $sql .= " WHERE (file_active=1)";
   else
   {
      query_db("UPDATE tags_tbl SET tag_requests = tag_requests+1 WHERE tag_name='$tag'");
      $sql .= " INNER JOIN file_tags_tbl ON (files_tbl.file_key = file_tags_tbl.file_id)";
	   $sql .= " INNER JOIN tags_tbl ON ( tags_tbl.tags_key = file_tags_tbl.tag_id )";
      $sql .= " WHERE(file_active=1) AND (tags_tbl.tag_name='$tag')";
   }

   $sql .= " GROUP BY files_tbl.file_key ORDER BY popularity DESC;";

   $total=0;
   $result = query_db($sql);
   $count = mysql_num_rows($result);

   echo "<h2>$count tutorials</h2>";
   
   while ($record = mysql_fetch_array($result)) {

      $file_key = $record["file_key"];
	   $file_author = $record["file_author"];
      $file = $record["file_path"]."/".$record["file_name"].".xml";
      $url = $htURL.'run/elcid.html?file='.URLEncode($file);
      $js = "parent.runTutorial('$file'); return false;";

      $sql2  = "select GROUP_CONCAT( tag_name SEPARATOR '; ' ) from tags_tbl";
	   $sql2 .= " INNER JOIN file_tags_tbl ON tags_tbl.tags_key = file_tags_tbl.tag_id";
      $sql2 .= " WHERE file_id=".$file_key;
	   $tags = query_one_item($sql2);
      if ($tags==false) 
		   $tagcell = "no tags";
	   else
			$tagcell = $tags;
      ?>
	      <div class="panel panel-default" style="display:inline-block; margin:5px;">
	         <h3><a target='_top' href='<?php echo $url;?>' onClick='<?php echo $js;?>' class="label label-primary">
			      <?php echo $record["file_name"];?>
		    	</a></h3>
			   <br />
		      <?php echo "<strong>Added on: </strong>" .$record["file_date"];?> <br>
		      <?php echo "<strong>By: </strong>".$record["file_author"];?><br>
		      <?php echo "<strong>Description: </strong>" .$record["file_description"];?>
            <div id="k<?php echo $file_key;?>" class="tags" onClick="editTags(this);" onMouseover="hitags(this);" onMouseout="lotags(this)">
			      <?php echo "<strong>Tags: </strong>" .$tagcell;?>
            </div>
	      </div>
		<?php
		$total++;
	} // finish showing the files
   close_db();  // on ferme la connexion

?>

