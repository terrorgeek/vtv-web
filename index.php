<?php
//http://api.cods.pyctex.net/ui/doc.html#__RefHeading__3485_479721004
/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require 'facebook-php-sdk/src/facebook.php';
require 'config.php';


// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => $fb_app_id,
  'secret' => $fb_secret,
));

// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
    $friends_profile = $facebook->api('/me/friends');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl();
}

// This call will always work since we are fetching public data.
//$naitik = $facebook->api('/naitik');

?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
  
  	<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
	<link href="css/styles2.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="js/custom-form-elements.js"></script>
    <script type="text/javascript">
		$(document).ready(function(){
			$(".friendList_cell").click(function(){
				if(!$(this).find(".friendsCheckbox").attr('checked')){
					$(this).addClass("friendList_cell_checked");
					$(this).find(".friendsCheckbox").attr("checked", true);
				}
				else{
					$(this).removeClass("friendList_cell_checked");
					$(this).find(".friendsCheckbox").attr("checked", false);					
				}
			})
			$(".selectAllCheckbox").click(function(){
	            if($(".styled").is(":checked")){
	            	$(".friendList_cell").each( function() {
						$(this).addClass("friendList_cell_checked");
						$(this).find(".friendsCheckbox").attr("checked", true);
	            	})
		        }else{
		        	$(".friendList_cell").each( function() {
						$(this).removeClass("friendList_cell_checked");
						$(this).find(".friendsCheckbox").attr("checked", false);
	            	})
				}             
			})
		});	
	</script>
    <title>php-sdk</title>
    <style>
      body {
        font-family: 'Lucida Grande', Verdana, Arial, sans-serif;
      }
      h1 a {
        text-decoration: none;
        color: #3b5998;
      }
      h1 a:hover {
        text-decoration: underline;
      }
      .friendList_cell_checked {background:url(images/listing_bgh.jpg) repeat-x 0 0 !important;}
      .friendsCheckbox{display:none;}
    </style>
  </head>
  <body>

    <?php if ($user): ?>
    <div id="page-wrap">
		<div class="content_pan">
		<?php 
			echo '<form enctype="multipart/form-data" action="ProgramBoard.php" method="POST">';
		?>
			<ul id="main-nav">
				<li class="tab1c"><a href="index.php"><img src="images/tab_ico1.png" /></a></li>
				<li class="tab2"><a href="#"><img src="images/tab_ico2.png" /></a></li>
				<li class="tab3"><a href="ProgramBoard.php"><img src="images/tab_ico3.png" /></a></li>
				<li class="tab4"><a href="schedule.php"><img src="images/tab_ico4.png" /></a></li>
				<li class="tab5"><a href="notification.php"><img src="images/tab_ico5.png" /></a></li>
				<li class="tab6"><a href="#"><img src="images/tab_ico6.png" /></a></li>
				<li class="tab7"><a href="#"><img src="images/tab_ico7.png" /></a></li>				
			</ul>
			<div class="clear">&nbsp;</div>
			<div class="tab_bottom_pan">
				<div class="tab_l"><h1>Choose your friends</h1>
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
				</div>
				<div class="tab_r">
					<span class="selectAllCheckbox">
						<input type="checkbox" name="1"  class="styled" />
					</span>			
					<span>select all</span>
					<span> 
					</span>			
					<span></span>
					<input type="submit" class="btn" value="Create List"/><br/>';
				</div>
				<div class="clear">&nbsp;</div>
			</div>
			
<!--	<a href="<?php echo $logoutUrl; ?>">Logout</a>-->
    <?php else: ?>
	<h1>Welcome to Kovue</h1>
      <div>
      	<strong><em>You are not Connected.</em></strong>
        <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
      </div>
    <?php endif ?>
<!-- 
    <h3>PHP Session</h3>
    <pre><?php print_r($_SESSION); ?></pre>
-->
    <?php if ($user): ?>
<!-- 
      <h3>You</h3>
      <img src="https://graph.facebook.com/<?php echo $user; ?>/picture">

      <h3>Your User Object (/me)</h3>
      <pre><?php print_r($user_profile); ?></pre>
-->      
		<ul class="listing">
		    <?php 
		        echo '<form enctype="multipart/form-data" action="schedule.php" method="POST">';
		    
				foreach ($friends_profile["data"] as $key => $value) {
					if($key%5 == 0){			
		        		echo '<li class="friendList_cell"><img src="https://graph.facebook.com/'.$value["id"].'/picture"/><span><span>'.$value["name"].'</span></span>';
		    ?>
				        		<div class="popup"> 
									<img src="https://graph.facebook.com/<?php echo $value["id"]?>/picture"/><span class="fl"> <h3>American Dad</h3>
								 	Waticng now</span> <img src="images/g_arrow.png" class="fr">
									<p><strong>She likes:</strong> <a href="#">Family Guy</a>, <a href="#">Friends</a>, <a href="#">Kill Bill</a>, <a href="#">Breaking Bad</a>, <a href="#">Sex and the City</a></p>
									<p><strong>You like in common:</strong> <a href="#">Family Guy</a>, <a href="#">Kill Bill</a></p>					
						  		</div>
			<?php
			            echo '<input type="checkbox" class="friendsCheckbox" name="fid[]" value="'.$value["id"].'" />';
				  		echo '<input type="checkbox" class="friendsCheckbox" name="fname[]" value="'.$value["name"].'" />';	
			            echo '</li>';
					}else { 
		        		echo '<li class="friendList_cell"><img src="https://graph.facebook.com/'.$value["id"].'/picture"/><span><span>'.$value["name"].'</span></span>';
		        		echo '<input type="checkbox" class="friendsCheckbox" name="fid[]" value="'.$value["id"].'" />';
		        		echo '<input type="checkbox" class="friendsCheckbox" name="fname[]" value="'.$value["name"].'" />';
		        		echo '</li>';
					}
				}
				echo '<br/>';
				echo '</form>';
		    ?>
		</ul> 
		
		   
	<?php endif ?>
    
  <!--
    <h3>Public profile of Naitik</h3>
    <img src="https://graph.facebook.com/naitik/picture">
    <?php //echo $naitik['name']; ?>
  -->
  
  			
			<div class="clear">&nbsp;</div>
		</div>
	</div>
  </body>
</html>
