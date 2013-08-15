<?php session_start();
$notification_id=$_POST["id"];
$notification_id="cc72c50c-adc4-a2f8-3c2a-505a1a9f20bd";
$ch = curl_init();
$url="http://api.cods-dev.ngb.biz/rest/v3/profile/notifications/".$notification_id."?session=".$_SESSION['user_session_id'];
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$notifications = curl_exec($ch);
$notifications = json_decode($notifications,true);
curl_close($ch);
print_r($notifications);
//echo $_SESSION['user_session_id'];
?>