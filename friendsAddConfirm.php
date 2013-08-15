<?php
/**
 * Copyright: Next Generation Boardband.Inc. 2012
 * @author: Fan Zhang, YUNYA SHEN
 * This php will mainly do the following things:
 * 1. accept all the parameters passed by friendsAdd_version.php.
 * 2. send request to facebook and create a friendslist called "kovue"
 * 3. add all selected friends to the friendslist "kovue"
 * 4. send all friends name, id to CODS to create profiles for friends.
 * 5. add friends' profiles to Friends, CODS
 * 
 * We use CURL to do the POST. And the Array should be processed by http_build_query. Be careful with CURL POST, make sure that you organize all your array 
 * into a URL format string. Str1&Str2&Str3&....
 */

require 'facebook-php-sdk/src/facebook.php';
require 'config.php';
session_start();
$access_token = $_SESSION['access_token'];
$friends_ids = $_POST['fid'];
$t = count($friends_ids);
$friend_list_id = null;

echo $_SESSION['currentUserSession'];
echo "<br>";




//Create facebook application instance.
$facebook = new Facebook(array(
  'appId'  => $fb_app_id,
  'secret' => $fb_secret,
  'cookie' => true,
));

//go over the existing friendslist
//find the friendslist called 'kovue' or create it
$friendListSet = $facebook->api('/me/friendlists');
$booleanExistKovue = false;
foreach ($friendListSet['data'] as $friendlist){
	if($friendlist['name']=='kovue'){
		$booleanExistKovue = true;
		$friend_list_id = $friendlist['id'];
	}
}
//create kovue friendslist
if(!$booleanExistKovue)
{
	$a = $facebook->api('/me/friendlists','POST',array(
	    'name' => 'kovue',
	    'access_token' => $access_token,
	    'privacy' => array('value' => 'EVERYONE')));
	
	$friend_list_id = $a['id'];
}



$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v3/profile?session=".$_SESSION['currentUserSession']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$testUserProfile = curl_exec($ch);
$testUserProfile = json_decode($testUserProfile,true);
curl_close($ch);
echo "<br><br>here we test the profile:<br>";
print_r($testUserProfile);
echo "<br>";


//get admin_session first from CODS:
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v3/login?login=APIuser&password=c72381ccfd9e53616d5fd76eceaca638");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$admin_session = curl_exec($ch);
$admin_session = json_decode($admin_session,true);
curl_close($ch);
echo "getting the admin session<br>";
print_r($admin_session);
echo "<br>";


//add friends

for($i=0; $i < $t; $i++){
        
    //creating profiles for new users when friends are added
    //Such profiles will be assigned automatically to new users if facebook_id matches, upon registration.
    $url = "http://api.cods.pyctex.net/rest/v3/profiles?session=".$admin_session['id'];
    
    $friend_profile = $facebook->api('/'.$friends_ids[$i]);
    $currentArray = array(
        "first_name"=>$friend_profile['first_name'], 
        "last_name"=>$friend_profile['last_name'],                     
        "facebook_id"=>$friends_ids[$i]
            );
    
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($currentArray));
    $friendCODSCred = curl_exec($ch);
    $friendCODSCred = json_decode($friendCODSCred,true);
    curl_close($ch);
    echo "<br>added friends' credential<br>";
    print_r($friendCODSCred);
   
    //if the profile already exist,we just need to retrive the profile id of the friend need to be added
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v3/profiles?session=".$_SESSION['currentUserSession']."&facebook_id=".$friends_ids[$i]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $friendid = curl_exec($ch);
    $friendid = json_decode($friendid,true);
    curl_close($ch);
    echo "<br>friend id retrived:<br>";
    print_r($friendid[entry_list][0][id]);
   
    //add the selected facebook friend to CODS friendlist
    $url = "http://api.cods.pyctex.net/rest/v3/profile/friends?session=".$_SESSION['currentUserSession'];
    $post_data=array(
        $friendid[entry_list][0][id]=>array("description"=>"Added as test friend")
        );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post_data));
    $userCODSFriends= curl_exec($ch);
    if ($userCODSCred === FALSE) {
            echo "cURL Error: " . curl_error($ch);
    }
    $userCODSFriends = json_decode($userCODSFriends,true);
    curl_close($ch);
    echo "<br><br>here is the result after we add friends<br>";
    print_r($userCODSFriends);

    //add to facebook friendlist"kovue"
    $facebook->api('/'.$friend_list_id .'/members/'.$friends_ids[$i],'POST',array('access_token' => $access_token,'privacy' => array('value' => 'EVERYONE')));
                
    
}

echo "<br><br>Let's test retrieving friends:<br>";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v3/profiles/".$testUserProfile['id']."/friends?session=".$_SESSION['currentUserSession']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$friends = curl_exec($ch);
$friends = json_decode($friends,true);
curl_close($ch);
print_r($friends);

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

.friendList_cell:hover {box-shadow: 0 0 3px 3px rgba(0, 0, 0, 0.3);}

.frientList_person {height: 36px;position: absolute;width: 36px;}

.frientList_name {margin: 4px 4px 4px 40px;word-wrap:break-word;word-break:normal;}

.friendList_cell_checked { background: -moz-linear-gradient(center top , #63ABF7, #6495ED) }

.friendsCheckbox{display:none; }
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

$user_ids=array();
echo '<ul>'; 

$myFriendList = $facebook->api('/'.$friend_list_id.'/members','GET');
foreach ($myFriendList["data"] as $value) {
    $existUser=0;
    
        if($existUser==0){
        array_push($user_ids, $value["id"]);
       
	echo '<li class="friendList_cell uki-dataGrid-cell">';
	echo '<img class="frientList_person"  src="https://graph.facebook.com/' . $value["id"] . '/picture"/>';
	echo '<div class="frientList_name">'.$value["name"].'</div>';
	echo '<input type="checkbox" class="friendsCheckbox" name="fid[]" value="'.$value["id"].'" />';
	echo '</li>';
        }
}
echo '</ul>';
?>
    
    
    
</div>
    
<br>


<div id="fb-root"></div>

<script src="http://connect.facebook.net/en_US/all.js"></script>
    
    <p>The above friends in your friends list haven't created vTV account yet<br>
    	Click and send them an invitation to join<br>
    	
      <input type="button"
        onclick="sendRequestToRecipients(); return false;"
        value="Send to Those friends"
      />
    </p>
      
    <p>If you want to select form all your friends, use the button below:<br>
    <input type="button"
           onclick="sendRequestViaMultiFriendSelector(); return false;"
           value="Select from all friends and send"
    />
    </p>
    
    <script>
      FB.init({
        appId  :'394859837223603',
        status :true,
        cookie :true,
        oauth  :true,
        frictionlessRequests: true
      });

      function sendRequestToRecipients() {
        FB.ui({method: 'apprequests',
          message: 'Join vTV with me',
          to:<?php echo $selectuser_ids; ?>
        }, requestCallback);
      }

      function sendRequestViaMultiFriendSelector() {
        FB.ui({method: 'apprequests',
          message: 'Join vTV with me',
          to:<?php echo $user_ids; ?>
        }, requestCallback);
      }
      
      function requestCallback(response) {
        console.log(response);
      }
    </script>    
         
  


</body>
</html>
