<?php session_start();
require 'facebook-php-sdk/src/facebook.php';
require 'config.php';
$requested_channel_id=$_POST["requested_channel_id"];
$program_id=$_GET['program_id'];
$url="http://api.cods-dev.ngb.biz/rest/v3/profiles/".$_SESSION['user_profile_id']."?session=".$_SESSION['user_session_id'];
$data = array("requested_channel_id"=>$requested_channel_id);
$data_string = json_encode($data);
$ch=curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER,
            array('Content-Type:application/json',
                  'Content-Length: '.strlen($data_string))
            );
$result = curl_exec($ch);
curl_close($ch);
echo $result;
echo "<br/>-----------------------------------------------------------<br/>";
echo $requested_channel_id;
//echo "session:".$_SESSION['user_session_id'];
?>