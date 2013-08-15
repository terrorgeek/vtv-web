<?php
/**
 * Copyright: Next Generation Boardband.Inc. 2012
 * @author: Fan Zhang, YUNYA SHEN
 * This is notification fetching center. Ajax request will be sent by client and this script fetch notifications for client
 * A JSON will be returned. one notification compress: type: request, reminder or notification; message; sender's avatar
 */

session_start();
//specify the start_time. Then we know which notification is already pulled.
//the $start_time will be the last point in time among all returned notifications.
$start_time = $_GET['start_time'];
if($start_time==null||$start_time=="")
	$start_time = '2007-01-01 00:00:00';
//track the last point of time, this will be returned to the client and in the next request, this is the start time
$track_last_pointoftime = $start_time;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://api.cods-dev.ngb.biz/rest/v3/profile/notifications?session=".$_SESSION['user_session_id']."&numentries=20");
//curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v2/".$_SESSION['user_session_id']."/profile/notifications");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$notifications = curl_exec($ch);
$notifications = json_decode($notifications,true);
curl_close($ch);
//formating the response
$response = array();
foreach ($notifications['entry_list'] as $value){
	if($value['date_modified']>$start_time){
		if($track_last_pointoftime<=$value['date_modified'])
			$track_last_pointoftime = $value['date_modified'];
		
		if(($value['from_id']==$value['to_id'])&&($value['status']=='new')){
			//this is a reminder.
			//icon use system default icon. we can pick better icons
			$tempArray = array(
					'type' => 'reminder',
					'message' => "<span id='span_for_notification_id' style='color:black;'>".$value['description']."
					<input type='hidden' value='".$value['id']."'/></span>",
					'icon' => 'png/onebit_41.png'
			);
			array_push($response, $tempArray);
		}             
		elseif(($value['to_id']==$_SESSION['user_profile_id'])&&($value['status']=='new')){
			//get the sender information(not me)
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://api.cods-dev.ngb.biz/rest/v3/profile/".$value['from_id']."?session=".$_SESSION['user_session_id']);
			//curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v2/".$_SESSION['user_session_id']."/profiles/".$value['from_id']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$sender = curl_exec($ch);
			$sender = json_decode($sender,true);
			curl_close($ch);
			//Print_r($sender);
			
			//this is a request
			$tempArray = array(
					'type' => 'requests',
					'message' =>$sender['name']." says : ".$value['description'].
					" <a class='requestAccept'><input type='hidden' value='".$value['id']."'/>Accept</a>
					<a class='requestDecline'><input type='hidden' value='".$value['id']."'/>Decline</a>",
					'icon' => $sender['avatar_url']
			);
			array_push($response, $tempArray);
		}
		elseif(($value['from_id']==$_SESSION['user_profile_id'])&&($value['status']!='read')){
			//get the receiver information(not me)
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://api.cods-dev.ngb.biz/rest/v3/profile/".$value['to_id']."?session=".$_SESSION['user_session_id']);
			//curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v2/".$_SESSION['user_session_id']."/profiles/".$value['to_id']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$receiver = curl_exec($ch);
			$receiver = json_decode($receiver,true);
			curl_close($ch);
				
			//this is a request
			$tempArray = array(
					'type' => 'notifications',
					'message' =>"To: ".$receiver['name'].", status: ".$value['status'].", description: ".$value['description']
					."<input type='hidden' value='".$value['id']."'/>",
					'icon' => $receiver['avatar_url']
			);
			array_push($response, $tempArray);
		}
		
	}
}
//print_r($response);
$response = json_encode(array('data'=>$response,'start_time'=>$track_last_pointoftime));
echo $response;
?>