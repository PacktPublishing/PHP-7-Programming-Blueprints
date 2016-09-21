<?php
session_start(); 
?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <title>Login to SocialExample.info</title>
<link href=" https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"> 
 </head>
  <body>
  <?php if ($_SESSION['FBID']): ?>      <!--  After user login  -->
<div class="container">
<div class="hero-unit">
  <h1>Hello <?php echo $_SESSION['UserName']; ?></h1>
  <p>How to login with PHP</p>
  </div>
<div class="span4">
 <ul class="nav nav-list">
<li class="nav-header">FB ID</li>
<li> Welcome <?php echo $_SESSION['FB_fullName’]; ?></li>
<div><a href="logout.php">Logout</a></div>
</ul></div></div>
    <?php else: ?>     <!-- Before login --> 
<div class="container">
<h1>Login with Facebook</h1>
           Not Connected
<div>
      <a href="fbconfig.php">Login with Facebook</a></div>
      </div>
    <?php endif ?>
  </body>
</html>

