<?php
/*
 * Copyright: Next Generation Boardband.Inc. 2012
 * @author: YUNYA SHEN
 * This php will mainly handle operations that can only be conducted by the the system administrator
*/
  //get admin_session from CODS:
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v3/login?login=APIuser&password=c72381ccfd9e53616d5fd76eceaca638");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$admin_session = curl_exec($ch);
$admin_session = json_decode($admin_session,true);
curl_close($ch);
echo "getting the admin session<br>";
print_r($admin_session);
echo "<br>";
       
 
 ?>



<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
       
       
    </body>
</html>
 