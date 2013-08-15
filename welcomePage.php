<?php
/**
 * Copyright: Next Generation Boardband.Inc. 2012
 * @author: Fan Zhang, YUNYA SHEN
 * This is the welcome page. Basically, if the user was login with facebook account, the index.php will redirect 
 * user to this page. If the user is a new user to CODS, he should register him an account by providing a PIN
 * If the user is already a user, login with his password and we will take him to his program borad page.
 */
require 'facebook-php-sdk/src/facebook.php';
require 'config.php';
session_start();

//$access_token = $_SESSION['access_token'];
//$access_token = htmlspecialchars($_GET["access_token"]);

//Create facebook application instance.
$facebook = new Facebook(array(
		'appId'  => $fb_app_id,
		'secret' => $fb_secret,
		'cookie' => true,
));

//$sent = false;

$user = $facebook->getUser();
$user_profile = $facebook->api('/me');
?>

<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <script src="jquery/jquery-1.7.2.js" type="text/javascript"></script>
    <script src="jquery/jquery.md5.js" type="text/javascript"></script>
    <title>Add your friends to a list</title>
    <script type="text/javascript">
	$(document).ready(function(){
		$("#loginForm").submit(function(){
			$("#userPIN").val($.md5($("#userPIN").val()));
		})
	});	
	</script>
  </head>
  <body>
  <div>
      
  <p>Welcome, <?php echo $user_profile['name'];?></p>
  <p>If you are already our customer, login with your password:</p>
  <form id='loginForm' action="ProgramBoard.php" method="POST" >
  	<input type="hidden" name="email" value="<?php echo $user_profile['email']?>">
  	<input type="password" name="password" id="userPIN"/>
  	<input type="submit" value='submit'/>
  </form>
  
  <p>For a new user, create your password and start.Existing Users can also add friends from here.</p>
  <form action="registration.php" method="POST" >
  	<input type="hidden" name="email" value="<?php echo $user_profile['email']?>">
  	<input type="hidden" name="first_name" value="<?php echo $user_profile['first_name']?>">
  	<input type="hidden" name="last_name" value="<?php echo $user_profile['last_name']?>">
  	<input type="hidden" name="facebook_id" value="<?php echo $user_profile['id']?>">
  	<input type="password" name="password" />
  	<input type="submit" value='submit'/>
  </form>
  
 
  <p>If you are System administrator (would be eliminated from app and built individually later),login with your password:</p>
  <form id='loginForm' action="adminlogin.php" method="POST" >
  	<input type="text" name="user name" value="APIuser">
  	<input type="password" name="password" id="userPIN"/>
  	<input type="submit" value='submit'/>
  </form>
  
  
  </div>
  
  </body>
</html>