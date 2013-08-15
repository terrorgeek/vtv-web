<?php
/**
 * Copyright: Next Generation Boardband.Inc. 2012
 * @author: Fan Zhang, YUNYA SHEN
 * This is the index page. Facebook will redirect user to this page when login or access our apps
 * If the user is already login with facebook, we redirect him to our login page
 * If not, we direct user to facebook login.
 */
require 'facebook-php-sdk/src/facebook.php';
require 'config.php';

//Create facebook application instance.
$facebook = new Facebook(array(

  'appId'  => $fb_app_id,
  'secret' => $fb_secret,
  'cookie' => true,
));
// Get User ID
$user = $facebook->getUser();
$loginUrl = $facebook->getLoginUrl();
// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
  	//$access_token = $facebook->getAccessToken();
  	//header('Location:friendsAdd.php?access_token='+$access_token);
  	session_start();
  	$_SESSION['access_token'] = $facebook->getAccessToken();
  	//header('Location:friendsAdd_v1.php');
  	header('Location:welcomePage.php');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}
else{
	try {?>
	 <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
	<?php
	}catch (FacebookApiException $e) {
    	error_log($e);
    	$user = null;
  	}
}

?>
