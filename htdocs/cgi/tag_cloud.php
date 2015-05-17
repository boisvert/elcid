<?php

  // select database  
  open_db();

  // Get tag stats
  $sql = "SELECT AVG(tag_requests) FROM tag";

  $avg_tag_weight = max(query_one_item($sql),1); // at least 1 to avoid div by 0 errors
     

  // Make the query
  $sql = "SELECT tag.tag, Count(1) AS tag_use, tag_requests";
  $sql .= " FROM tag, file_tag, file";
  $sql .= " WHERE tag.tag = file_tag.tag AND file.file_id=file_tag.file_id AND file_active=1";
  $sql .= " GROUP BY tag.tag ORDER BY tag_use DESC;";

  
  $result = query_db($sql);

  //counter

  while ($record = $result -> fetch_array()) {
     $tagweight = $record["tag_requests"];
     $tagweight = round(150*sqrt($tagweight/$avg_tag_weight));
     $tagname = $record["tag"];
     $url = 'index.php?tag='.urlencode($tagname);
     $style="font-size:".max($tagweight,60)."%;";

    if ($tag == $tagname) // $tag is the current tag being shown
        echo " <a style='".$style."'>".str_replace(" ","&nbsp;",$tagname)."</a> \n";
     else {
       $url = 'index.php?tag='.urlencode($tagname);          
        echo " <a href='".$url."' style='".$style."'>".str_replace(" ","&nbsp;",$tagname)."</a> \n";
     }
  } // finish showing the files

  close_db();  // on ferme la connexion

?>
