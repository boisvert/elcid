<?php

  // select database  
  open_db();

  // Get tag stats
  $sql = "SELECT AVG(tag_requests) FROM tags_tbl";

  $avg_tag_weight = max(query_one_item($sql),1); // at least 1 to avoid div by 0 errors
     

  // Make the query
  $sql = "SELECT tag_name, Count(1) AS tag_use, tag_requests";
  $sql = $sql." FROM tags_tbl, file_tags_tbl, files_tbl";
  $sql = $sql." WHERE tags_key=tag_id AND file_key=file_id AND file_active=1";
  $sql = $sql." GROUP BY tags_key ORDER BY tag_use DESC;";

  $result = query_db($sql);

  //counter

  while ($record = mysql_fetch_array($result)) {
     $tagweight = $record["tag_requests"];
     $tagweight = round(150*sqrt($tagweight/$avg_tag_weight));
     $tagname = $record["tag_name"];
     $url = 'index.php?tag='.urlencode($tagname);
     $style="font-size:".max($tagweight,60)."%;";

	 if ($tag == $tagname) // $tag is the current tag being shown
        echo " <a style='".$style."'>".str_replace(" ","&nbsp;",$tagname)."</a> \n";
     else {
	    $url = 'index.php?tag='.urlencode($tagname);          
        echo " <a href='".$url."' style='".$style."'>".str_replace(" ","&nbsp;",$tagname)."</a> \n";
     }
  } // finish showing the files

  mysql_close();  // on ferme la connexion

?>
