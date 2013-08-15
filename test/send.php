<?php session_start();
$ch = curl_init();
$data_array=array();
$data_string=json_encode($data_array);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER,
            array('Content-Type:application/json',
                  'Content-Length: '.strlen($data_string))
            );
curl_setopt($ch, CURLOPT_URL, "http://api.cods-dev.ngb.biz/rest/v3/channels?session=".$_SESSION['user_session_id']."&numentries=100");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$channels = curl_exec($ch);
$channels= json_decode($channels,true);
curl_close($ch);
?>