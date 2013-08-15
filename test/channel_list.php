<?php session_start();
require 'facebook-php-sdk/src/facebook.php';
require 'config.php';
echo "<strong>channels:</strong><br/>";
$ch = curl_init();
$session=$_SESSION["user_session_id"];
//curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v3/channels?session=".$_SESSION['user_session_id']."&numentries=12");
curl_setopt($ch, CURLOPT_URL, "http://api.cods-dev.ngb.biz/rest/v3/channels?session=".$session."&numentries=1000");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$channels = curl_exec($ch);
echo $channels;
$channels= json_decode($channels,true);
curl_close($ch);
// print_r($channels);
echo "<strong>channel name------callsign------channel_id</strong><br/><br/>";
foreach ($channels[entry_list] as $value){
	echo "<a href='see_single_channel.php?requested_channel_id=".$value['id']."'>".$value['id']."</a>--------".$value["callsign"]."--------".$value['id']."<br/>"; 
}
