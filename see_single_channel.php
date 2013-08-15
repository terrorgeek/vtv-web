<?php session_start();
require 'facebook-php-sdk/src/facebook.php';
require 'config.php';
$requested_channel_id=$_GET["requested_channel_id"];
//$url="http://api.cods.pyctex.net/rest/v3/profiles/12bb9aa9-fbdd-1e0a-c53a-4fff6358f941?session=".$_SESSION['user_session_id'];
$url="http://api.cods-dev.ngb.biz/rest/v3/profiles/12bb9aa9-fbdd-1e0a-c53a-4fff6358f941?session=".$_SESSION['user_session_id'];
$data = array("requested_channel_id"=>$requested_channel_id,
              "request_channel_id"=>"",
              "current_channel_id2"=>"",
              "_current_channel_id"=>"",
              "test2"=>"",
              "channel"=>"");
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
print_r($result);
echo "<br/><br/><br/><br/>";
?>
<a href="channel_list.php">go back to channel list</a>
