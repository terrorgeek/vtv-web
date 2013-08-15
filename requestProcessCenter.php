<?php
/**
 * Copyright: Next Generation Boardband.Inc. 2012
 * @author: Fan Zhang, YUNYA SHEN
 * This is request processing center. 
 * type[num]
 * num, action, notifications status 
 * 1, accept request, change status to "accepted"
 * 2, declined request, change status to "declined"
 * 3, mark 'read' to the request, reminder or notification, change status to "readed". If 'readed', the sender will not be notified
 * 4, create a new notification.
 * 5, schedule a program
 */
session_start();

if($_GET['type']==1){
	//mark the notification as "Accepted"
	$url="http://api.cods.pyctex.net/rest/v3/profiles/".$_GET['id']."/notifications?session=".$_SESSION['user_session_id'];
	//$url = "http://api.cods.pyctex.net/rest/v2/".$_SESSION['user_session_id']."/notifications/".$_GET['id'];
	$post_data = array (
			"status"=>"accepted"
	);
	$post_data = json_encode($post_data);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	$acceptNotif = curl_exec($ch);
	$acceptNotif = json_decode($acceptNotif,true);
	curl_close($ch);
	//send a reminder to the user, get the notification first.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v3/profiles/".$_GET['id']."/notifications?session=".$_SESSION['user_session_id']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$notification = curl_exec($ch);
	$notification = json_decode($notification,true);
	curl_close($ch);
	//find the sender
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v3/profiles/".$notification['from_id']."?session=".$_SESSION['user_session_id']);
	//curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v2/".$_SESSION['user_session_id']."/profiles/".$notification['from_id']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$sender = curl_exec($ch);
	$sender = json_decode($sender,true);
	curl_close($ch);
	//construct the reminder
	
	$url = "http://api.cods.pyctex.net/rest/v3/notifications?session=".$_SESSION['user_session_id'];
	//$url = "http://api.cods.pyctex.net/rest/v2/".$_SESSION['user_session_id']."/notifications";
	$post_data = array (
			"name"=>$notification['name'],
			"description"=>"From: ".$sender['name'].". Says:".$notification['description'],
			"from_id"=>$notification['to_id'],
			"to_id"=>$notification['to_id'],
			"subject_type"=>$notification['subject_type'],
			"subject_id"=>$notification['subject_id'],
			"status"=>"new"
	);
	$post_data = json_encode($post_data);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	$reminder = curl_exec($ch);
	//$reminder = json_decode($reminder,true);
	curl_close($ch);
	//construct a array for client to display the notification
	/* $response = array(
			'type' => 'reminder',
			'message' => $reminder['description'],
			'icon' => 'png/onebit_41.png'
	); */
	//$response = json_encode($response);
	echo $reminder;
}
if($_GET['type']==2){
	//mark the notification as "Declined"
	$url = "http://api.cods.pyctex.net/rest/v3/profiles/".$_GET['id']."/notifications?session=".$_SESSION['user_session_id'];
	//$url = "http://api.cods.pyctex.net/rest/v2/".$_SESSION['user_session_id']."/notifications/".$_GET['id'];
	$post_data = array (
			"status"=>"declined"
	);
	$post_data = json_encode($post_data);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	$declinedNotif = curl_exec($ch);
	curl_close($ch);
	echo $declinedNotif;
	
}
if($_GET['type']==3){
	//mark the notification as "read"
	$url = "http://api.cods.pyctex.net/rest/v3/profiles/".$_GET['id']."/notifications?session=".$_SESSION['user_session_id'];
	//$url = "http://api.cods.pyctex.net/rest/v2/".$_SESSION['user_session_id']."/notifications/".$_GET['id'];
	$post_data = array (
			"status"=>"read"
	);
	$post_data = json_encode($post_data);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	$readedNotif = curl_exec($ch);
	curl_close($ch);
	echo $readedNotif;

}
if($_GET['type']==4){
	$url = "http://api.cods.pyctex.net/rest/v3/notifications?session=".$_SESSION['user_session_id'];
	//$url = "http://api.cods.pyctex.net/rest/v2/".$_SESSION['admin_session_id']."/notifications";
	$post_data = array (
			"name"=>"Watch together",
			"description"=>"Watch this program with me",
			"from_id"=>$_SESSION['user_profile_id'],
			"to_id"=>$_GET['to_id'],
			"subject_type"=>"guides",
			"subject_id"=>$_GET['guide_id'],
			"status"=>"new"
	);
	$post_data = json_encode($post_data);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	$newNotification = curl_exec($ch);
	$newNotification = json_decode($newNotification,true);
	curl_close($ch);
	echo $newNotification;
}
if($_GET['type']==5){
 $url = "http://api.cods.pyctex.net/rest/v3/profile/schedule?session=" . $_SESSION['user_session_id'];
  $postArray = array(
  "2e812daa-2bcf-aeea-ac85-5050a81dbd14" => array("description" => "Don't forget to watch this movie!",
  "start_time" => "")
  );
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postArray));
  $back = curl_exec($ch);
  $back = json_decode($back, true);
  curl_close($ch);
  echo "<br>add schedule back:<br>";
  print_r($back); 
}

?>