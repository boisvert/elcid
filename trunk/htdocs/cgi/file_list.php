<?php

  // debug mode: uncomment to get debugging information
  //$debug = true;

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
      var tags = elt.innerHTML.trim();
      if (tags == 'no tags') tags = '';
      newTags = prompt("Edit the tags:", tags.trim());
      if (newTags!=null && newTags!=tags) setTags(elt.id, newTags);
   }
   else {
      alert("Login or register to edit the tags.");
   }
}

function setTags(fileId, tags) {
   var fileKey = fileId.substring(1);
   var parameters = "file="+fileKey+"&tags="+encodeURI(tags);
   tagsRequest = parent.POSTRequest('cgi/set_tags.php', parameters, setTagsResponse);
}

function setTagsResponse() {
   if (tagsRequest.readyState == 4) {
      if (tagsRequest.status == 200) {   
         //alert(tagsRequest.responseText);
         window.location=window.location+" ";
      }
      else
         alert('There was a problem settings the tags.\n'+tagsRequest.status+'\n'+tagsRequest.responseText);
   }
}

</script>

<?php

   // select database  
   open_db();

   // Make the query
   $sql  = "SELECT file.*, count(1) AS popularity, avg(rate) as stars,";
   $sql .= " (select GROUP_CONCAT(tag SEPARATOR '; ' ) from file_tag where file_tag.file_id=file.file_id) as tagset";
   $sql .= " FROM file LEFT JOIN file_use ON (file.file_id = file_use.file_id)";
   $sql .= " LEFT JOIN file_rating ON (file.file_id = file_rating.file_id)";

   if ($tag == "")
   {
      $sql .= " WHERE (file_active=1)";
   }
   else
   {
      query_db("UPDATE tag SET tag_requests = tag_requests+1 WHERE tag.tag='$tag'");
      $sql .= " INNER JOIN file_tag ON (file.file_id = file_tag.file_id)";
      $sql .= " WHERE (file_active=1) AND (file_tag.tag='$tag')";
   }

   $sql .= " GROUP BY file.file_id ORDER BY popularity DESC;";

   $total=0;
   $result = query_db($sql);
   $count = mysqli_num_rows($result);

   echo "<h2> $count tutorials</h2>";

   while ($record = mysqli_fetch_array($result)) {

      $file_id = $record["file_id"];
      $file_author = $record["file_author"];
      $file = $record["file_path"]."/".$record["file_name"].".xml";
      $url = $htURL.'run/elcid.html?file='.URLEncode($file);
      $js = "runTutorial('$file'); return false;";
      $tagset = $record["tagset"];
      if (!$tagset) 
   $tagset = "no tags";
      ?>
         <div class="panel col-md-3" style="margin:5px;">
            <h3><a onClick="<?php echo $js;?>" class="label label-primary" href='<?php echo $url;?>'>
               <?php echo $record["file_name"];?>
            </a></h3>
            <br />
            Added on <?php echo $record["file_date"];?> <br />
            by <?php echo $record["file_author"];?><br />
            <?php echo $record["file_description"];?><br />
            Tags: 
            <span id="k<?php echo $file_id;?>" class="tags" onClick="editTags(this);" onMouseover="hitags(this);" onMouseout="lotags(this)">
         <?php echo $tagset;?>
            </span>
         </div>
         

<?php
$total++;
   } // finish showing the files
   close_db();  // on ferme la connexion

?>

