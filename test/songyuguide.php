<?php session_start();
require 'facebook-php-sdk/src/facebook.php';
require 'config.php';

$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v3/channels?session=".$_SESSION['user_session_id']."&numentries=12");
curl_setopt($ch, CURLOPT_URL, "http://api.cods-dev.ngb.biz/rest/v3/channels?session=".$_SESSION['user_session_id']."&numentries=20");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$channels = curl_exec($ch);
$channels= json_decode($channels,true);
curl_close($ch);
 print_r(count($channels["entry_list"]));
for ($i=0; $i <count($channels["entry_list"]) ; $i++) { 
	echo $channels["entry_list"][$i][id]."-----".$channels["entry_list"][$i]["name"]."<br/>";
}
