<?php
/**
 * Copyright: Next Generation Boardband.Inc. 2012
 * @author: Fan Zhang, YUNYA SHEN
 * This is the php script that retrieve friends information from CODS and echo to the client
 */
session_start();
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v3/profile/friends?session=".$_SESSION['user_session_id']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$friends = curl_exec($ch);
//$friends = json_decode($friends,true);
curl_close($ch);
echo $friends;


?>