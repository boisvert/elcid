<script type="text/javascript" src="run/config.js"> </script>
<script type="text/javascript" src="images/behaviour.js"> </script>
<script type="text/javascript" src="images/monitor.js"> </script>


<script LANGUAGE="JavaScript" src="images/loggedin.php" type="text/javascript"> </script>

<div class="container">

   <div class="col-md-3">
      <a href="index.php"><img src="images/logo.jpg" height="100" class="img-responsive" /></a>
   </div>
   
   <div class="col-md-7" style="background:#00AF64; color:#FFFFFF; border-radius: 5px;">

      <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
         <!-- Indicators -->
         <ol class="carousel-indicators">
            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
            <li data-target="#carousel-example-generic" data-slide-to="2"></li>
         </ol>

         <!-- Wrapper for slides -->
         <div class="carousel-inner">
            <div class="item active">
               <img src="images/1.jpg" alt="...">
            </div>
            <div class="item">
               <img src="images/2.jpg" alt="...">
            </div>
            <div class="item">
               <img src="images/3.jpg" alt="...">
            </div>
         </div>

         <!-- Controls
         <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
         </a>
         <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
         </a>
         -->
      </div>
   </div>
</div>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="navbar-inner">
      <div class="container">
         <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
               <span class="sr-only">Toggle navigation</span>
               <span class="icon-bar"></span>
               <span class="icon-bar"></span>
               <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">Home</a>
         </div>
         <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
               <li><a href="about.php">About eL-CID</a></li>            
               <li><a href="project.php">Project</a></li>
               <li><a href="contact.php">Contact</a></li>
               <!-- li><a href="search.php">Search</a></li -->
            </ul>
            <ul id="login" class="nav navbar-nav navbar-right"></ul>          
         </div> <!--/.nav-collapse -->
      </div>
   </div>
</div>

<script type="text/javascript">
   welcomeLogin();
</script>


