<?php
/**
 * Copyright: Next Generation Boardband.Inc. 2012
 * @author: Fan Zhang, zfwise@gwmail.gwu.edu
 * acutally, This page serves as test purpose now. 
 * It will be the program board page in the future.
 */
/* echo "welcome:<br>";
echo $_POST['email'];

echo "<br>let's retrieve your identity:<br>";

//test user's credential:
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v2/login/".$_POST['email']."/".$_POST['password']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$testUserCred = curl_exec($ch);
$testUserCred = json_decode($testUserCred,true);
curl_close($ch);
echo "here we test this user:<br>";
print_r($testUserCred);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v2/".$testUserCred['id']."/profile");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$testUserProfile = curl_exec($ch);
$testUserProfile = json_decode($testUserProfile,true);
curl_close($ch);
echo "<br>here we test the profile:<br>";
print_r($testUserProfile); */

/* $url = "http://api.cods.pyctex.net/rest/v2/".$testUserCred['id']."/profiles/";
//do a post. Construct an array like this and use the http_build_query to encode this array into URL formation
$post_data = array("entries"=>array(array("first_name"=>"Hao","last_name"=>"Huan1","facebook_id"=>"1234602"),array("first_name"=>"Hao","last_name"=>"Huan1","facebook_id"=>"1234603")));
$post_data=http_build_query($post_data);
//if we want to do a PUT, using the following two lines.
//$post_data = array("first_name"=>"Hao","last_name"=>"Huang7","facebook_id"=>"1234579");
//$post_data1 = json_encode($post_data);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length:'.strlen($post_data1)));
$userCODSCred = curl_exec($ch);
if ($userCODSCred === FALSE) {
	echo "cURL Error: " . curl_error($ch);
}
$userCODSCredArray = json_decode($userCODSCred,true);
curl_close($ch);
echo "<br>here is the result after we upload the friendslist to CODS<br>";
print_r($userCODSCred);
//manual construction of ids
$post_data = array();
foreach($userCODSCredArray['ids'] as $value){
	array_push($post_data, $value);
}
$post_data = array("ids"=>$post_data);
print_r($post_data);
//put all those IDs into friends.
$url = "http://api.cods.pyctex.net/rest/v2/".$testUserCred['id']."/profiles/".$testUserProfile['id']."/friends/";
//$post_data = $userCODSCred;
$post_data=http_build_query($post_data);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
$userCODSFriends= curl_exec($ch);
if ($userCODSCred === FALSE) {
	echo "cURL Error: " . curl_error($ch);
}
$userCODSFriends = json_decode($userCODSFriends,true);
curl_close($ch);
echo "<br><br>here is the result after we add friends<br>";
print_r($userCODSFriends);   */

/* echo "<br><br>Here to test friends user:Enrique Yu :<br>";
$hardCodedTestUser = '1217a51c-b9fc-3cfa-e0bd-4fff6350250b';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v2/".$testUserCred['id']."/profiles/".$hardCodedTestUser."/friends");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$friends = curl_exec($ch);
$friends = json_decode($friends,true);
curl_close($ch);
print_r($friends);

echo "<br><br>Here to test the schedule of user:Enrique Yu :<br>";
$hardCodedTestUser = '1217a51c-b9fc-3cfa-e0bd-4fff6350250b';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v2/".$testUserCred['id']."/profiles/".$hardCodedTestUser."/schedule");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$schedule = curl_exec($ch);
$schedule = json_decode($schedule,true);
curl_close($ch);
print_r($schedule); */

echo "<br><br>Here to test the notification of user, POST<br>";
$hardCodedTestUser = '1217a51c-b9fc-3cfa-e0bd-4fff6350250b';
/* $ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v2/949fba5930d0e978517764657e87e758/profile/notifications");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$notification = curl_exec($ch);
$notification = json_decode($notification,true);
curl_close($ch);
print_r($notification); */

/* $url = "http://api.cods.pyctex.net/rest/v2/c6e3c45b593471166066d4781c802740/notifications";
$post_data = array (
		"name"=>"watch together", 
		"description"=>"I love this show", 
		"to_id"=>"99553b21-77ad-7b24-6c9b-5016da5ccaee",
		"from_id"=>"103782b6-aa79-f369-412a-4fff634bdd8b",
		"subject_type"=>"programs", 
		"subject_id"=>"2bd80442-2210-11c5-2caf-4fff80151928", 
		"status"=>"new"
); 
//$post_data=http_build_query($post_data);
$post_data = json_encode($post_data);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
$notification = curl_exec($ch);
$notification = json_decode($notification,true);
curl_close($ch);
//print_r($notification)； */


/* echo "<br><br>Here to test the notification of user, GET<br>";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v2/c6e3c45b593471166066d4781c802740/profile/notifications");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$notification = curl_exec($ch);
$notification = json_decode($notification,true);
curl_close($ch);
print_r($notification); */

/* foreach ($notification['entry_list'] as $field){
$url = "http://api.cods.pyctex.net/rest/v2/64a820d874890785e31b7957c2eaf331/notifications/".$field['id'];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
$result = curl_exec($ch);
$result = json_decode($result,true);
curl_close($ch);
print_r($result);
} */

$url = "http://api.cods.pyctex.net/rest/v2/5a2d9e104c0968611da11f955736eae7/channels/2ce4c1f0-753a-5a8a-f06e-5005e11879d8/guide?end_time=2012-08-16+18:00:00";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
$result = curl_exec($ch);
$result = json_decode($result,true);
curl_close($ch);
print_r($result);

$url = "http://api.cods.pyctex.net/rest/v2/5a2d9e104c0968611da11f955736eae7/profiles/99553b21-77ad-7b24-6c9b-5016da5ccaee/schedule";
$post_data = array (
		'guide_id' => "81effa9e-cbee-a486-8a4b-502bed70da47"
);
$post_data=http_build_query($post_data);
//$post_data = json_encode($post_data);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
$result = curl_exec($ch);
$result = json_decode($result,true);
curl_close($ch);
print_r($result);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script src="jquery/jquery-1.7.2.js" type="text/javascript"></script>
	<script type="text/javascript"  src="jquery/ui/jquery-ui-1.8.21.custom.js" ></script>
	<link rel="stylesheet" type="text/css" href="jquery/themes/pepper-grinder/jquery-ui-1.8.21.custom.css" />
    <link rel="stylesheet" type="text/css" href="css/style_light.css">
    <script src="jquery/ttw-notification-menu.min.js" type="text/javascript"></script>
    </head>
    <script type="text/javascript">
		$(document).ready(function(){	
			var notifications = new $.ttwNotificationMenu({
		        notificationList:{
		            anchor:'item',
		            offset:'0 15'
		        }
		    });

		    notifications.initMenu({
		    	Notifications:'#notifications',
		    	Remainder:'#remainder',
		    	Others:'#others'
		    });
		});
	</script>
	<style type="text/css">

        .ttw-notification-menu{
                width: 240px;
        }
    </style>

<body>

<ul class="ttw-notification-menu">
    <li id="notifications" class="notification-menu-item first-item"><a href="index.html#">Notifications</a></li>
    <li id="remainder" class="notification-menu-item"><a href="index.html#">Remainder</a></li>
    <li id="others" class="notification-menu-item last-item">
        <a href="index.html#">Others</a>
    </li>
</ul>
</body>
</html>