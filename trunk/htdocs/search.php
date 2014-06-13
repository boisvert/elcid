<?php
mysql_connect("localhost","guest","test") or die("Could not connect!");
mysql_select_db("el-cid") or die("Could not Database!");
$output = '';
//collect
if (isset($_POST['search'])) {
	$searchq = $_POST['search'];
	$searchq = preg_replace("#[^0-9a-z]#i","",$searchq);
	
	$query = mysql_query("SELECT * FROM files_tbl WHERE file_name LIKE '%$searchq%' OR file_author LIKE '%$searchq%'") or die("Could not search!");
	$count = mysql_num_rows($query);
	
		if ($count == 0) {
		$output = 'There are no search results!';
		}else{
			while($row = mysql_fetch_array($query)) {
				$tutorial = $row['file_name'];
				$author = $row['file_author'];
				$date = $row['file_date'];
				$description = $row['file_description'];
				$path = $row['file_path'];
				$file = ''.$path.'/'.$tutorial.'.xml';				
         		$url = $htURL.'run/elcid.html?file='.URLEncode($file);
         		$js = 'parent.runTutorial("'.$file.'"); return false;';
				
				$output .= '<div class="panel panel-default">
      	 	<div class="panel-body">
		 	<h3><a href="'.$url.'" onClick="'.$js.'" class="label label-primary">'.$tutorial.'</a></h3>
			<strong>Added on : </strong> '.$date.' <br>
		 	<strong>By : </strong> '.$author.' <br>
			<strong>Description : </strong> '.$description.' <br>
			</div>
		 	</div>';		
				
				
				}
			}
	}
?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>

<META http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>eL-CID :- Search</title>

<link href="css/bootstrap.css" rel="stylesheet">
<link rel="StyleSheet" href="css/style.css" type="text/css">

</head>

<body onload="monitor();">

<?php include("header.php"); ?>

<div class="container">

<ol class="breadcrumb">
You are here: 
  <li><a href="index.php">Home</a></li>
  <li class="active">Search</li>
</ol>

<div class="row">
  <div class="col-lg-6">
    <div class="input-group">
      <span class="input-group-btn">
      <form action="search.php" method="post">
        <input class="btn btn-default" type="submit">
      </span>
      <input type="text" class="form-control" name="search" placeholder="Search for Tutorials..." autofocus="autofocus" value="<?php echo $_POST['search']; ?>">
      </form>
    </div><!-- /input-group -->
  </div><!-- /.col-lg-6 --></div>

<!--<form action="search.php" method="post">

	<input type="text" name="search" placeholder="Search for Tutorials..." />
   <input type="submit" value="Go" class="btn btn-info" />
</form>-->
<br />

<?php print("$output");?>

</div>

<?php include("footer.php"); ?>

<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>
