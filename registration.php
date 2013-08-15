<?php
/**
 * Copyright: Next Generation Boardband.Inc. 2012
 * @author: Fan Zhang, YUNYA SHEN
 * This php will mainly do the following things:
 * 1. retrieve all friends of the user and display them
 * 2. let the user select friends, pass the friends id, name and so on to friendsAddConfirm.php to process.
 * 3. each friends cell was registered with a jquery event: click and select the friend
 * 4. two more parameters will be transmitted to the friendsAddConfirm.php: the session-id and user's CODS id. 
 */
require 'facebook-php-sdk/src/facebook.php';
require 'config.php';
session_start();
// $access_token = $_SESSION['access_token'];
//$access_token = htmlspecialchars($_GET["access_token"]);

//get admin_session first from CODS:
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v3/login?login=APIuser&password=c72381ccfd9e53616d5fd76eceaca638");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$admin_session = curl_exec($ch);
$admin_session = json_decode($admin_session,true);
print_r($admin_session);
curl_close($ch);

//register the user in CODS/CASE. And obtain user's identity in CODS

$email = $_POST['email'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$facebook_id = $_POST['facebook_id'];
$password = $_POST['password'];
$url = "http://api.cods-dev.ngb.biz:8888/rest/v3/register?session=".$admin_session['id'];

$post_data = array (
		"first_name" => $first_name,
		"last_name" => $last_name,
		"email" => $email,
		"facebook_id" => $facebook_id,
		"password" => $password
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
$userCODSCred = curl_exec($ch);
$userCODSCred = json_decode($userCODSCred,true);
curl_close($ch);
echo "<br>here is the user's credential<br>";
print_r($userCODSCred);


//test user's credential:
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://api.cods-dev.ngb.biz/rest/v3/login?login=".$email."&password=".md5($password));

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$testUserCred = curl_exec($ch);
$testUserCred = json_decode($testUserCred,true);
curl_close($ch);
$_SESSION['currentUserSession'] = $testUserCred['id'];
$_SESSION['currentUserCODSID'] = $testUserCred['name_value_list']['user_id']['value'];
echo "<br>here we test this user:<br>";
print_r($testUserCred);

//Create facebook application instance.
$facebook = new Facebook(array(
		'appId'  => $fb_app_id,
		'secret' => $fb_secret,
		'cookie' => true,
));



//$user = $facebook->getUser();       
// Proceed knowing you have a logged in user who's authenticated.
//$user_profile = $facebook->api('/me');
$friends_profile = $facebook->api('/me/friends');



//$myFriendList = $facebook->api('/me/friendlists');
//$list_name = "dragon_trainers";


?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>Add your friends to a list</title>
    <script src="jquery/jquery-1.7.2.js" type="text/javascript"></script>
	<script type="text/javascript"  src="jquery/ui/jquery-ui-1.8.21.custom.js" ></script>
	<link rel="stylesheet" type="text/css" href="jquery/themes/pepper-grinder/jquery-ui-1.8.21.custom.css" />
    <style>
    	ul, li {list-style: none outside none;padding: 0;}
    	.friendList_cell {background: -moz-linear-gradient(center top , #D3D3D3, #F4F4F4) repeat scroll 0 0 transparent;border: 1px solid #DDDDDD;border-radius: 2px 2px 2px 2px;box-shadow: 0 1px 0 rgba(0, 0, 0, 0.2);cursor:pointer; float: left; font: 13px arial,sans-serif;height: 36px;margin: 10px 20px 13px 0;padding: 4px;position: relative;vertical-align: top;width: 138px;word-wrap: break-word;}
		.friendList_cell:hover {box-shadow: 0 0 3px 3px rgba(0, 0, 0, 0.3); }
      	.frientList_person {height: 36px;position: absolute;width: 36px;}
		.frientList_name {margin: 4px 4px 4px 40px;word-wrap:break-word;word-break:normal;}
		.friendList_cell_checked { background: -moz-linear-gradient(center top , #63ABF7, #6495ED) }
		.friendsCheckbox{display:none;}
	</style>
    <script type="text/javascript">
	$(document).ready(function(){
		$(".friendList_cell").click(function(){
			if(!$(this).hasClass("friendList_cell_checked")){
				$(this).addClass("friendList_cell_checked");
				
				$(this).find(".friendsCheckbox").attr("checked", true);
			}
			else{
				$(this).removeClass("friendList_cell_checked");
				$(this).find(".friendsCheckbox").attr("checked", false);
			}
			})
	});	
	</script>
  </head>
  <body>
  	<div id="centralFriendsDiv">
	<?php
            echo '<form enctype="multipart/form-data" action="friendsAddConfirm.php" method="POST">';
            echo '<ul>';
            foreach ($friends_profile["data"] as $value) {
                echo '<li class="friendList_cell uki-dataGrid-cell">';
                echo '<img class="frientList_person"  src="https://graph.facebook.com/' . $value["id"] . '/picture"/>';
                echo '<div class="frientList_name">'.$value["name"].'</div>';
               // echo '<input type="hidden" name="first_name[]" value='.$value['id'].'/>';
               // echo '<input type="hidden" name="last_name[]" value='.$value['id'].'/>';
               // echo '<input type="hidden" name="name[]" value='.$value['name'].'/>';
                echo '<input type="checkbox" class="friendsCheckbox" name="fid[]" value="'.$value["id"].'" />';
                echo '</li>';
            }
            echo '</ul>';
            echo '<input type="submit" value="create list and register new friend in your vTV"/><br/>';
            echo '</form>';
            ?>
     </div>
  </body>
</html>
