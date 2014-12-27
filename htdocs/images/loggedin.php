<?php

// this script should be loaded as javascript
// method 1: name it as the source in a script tag
// method 2: use php include within script tags

   $loggedin=(isset($_COOKIE['user']));
   $username = ($loggedin)?$_COOKIE['user']:"";
   
   header('Content-Type: application/javascript');
?>

loggedin = <?php echo ($loggedin)?"true":"false"; ?>;
userName = "<?php echo ($loggedin)?$username:""; ?>";

<?php exit(); ?>

