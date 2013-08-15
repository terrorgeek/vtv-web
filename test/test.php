<?php session_start();
require 'facebook-php-sdk/src/facebook.php';
require 'config.php';
session_start();
date_default_timezone_set('US/Eastern');
function array_sort($arr,$keys,$type='asc'){
   $keysvalue = $new_array = array();
   foreach ($arr as $k=>$v){
      $keysvalue[$k] = $v[$keys];
   }
   if($type == 'asc'){
   asort($keysvalue);
   }else{
   arsort($keysvalue);
   }
    reset($keysvalue);
   foreach ($keysvalue as $k=>$v){
     $new_array[$k] = $arr[$k];
   }
   return $new_array;
}
$ch = curl_init();
echo "http://api.cods-dev.ngb.biz/rest/v3/channels?session=".$_SESSION['user_session_id']."&numentries=100";
curl_setopt($ch, CURLOPT_URL, "http://api.cods-dev.ngb.biz/rest/v3/channels?session=".$_SESSION['user_session_id']."&numentries=100");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$channels = curl_exec($ch);
$channels= json_decode($channels,true);
curl_close($ch);


$i=0;

foreach ($channels[entry_list] as $value){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://api.cods-dev.ngb.biz/rest/v3/channels/".$value[id]."/guide?session=".$_SESSION['user_session_id']."&end_time=".date('Y-m-d+H:i:s',strtotime('+10 hour'))."&start_time=".date('Y-m-d+H:i:s',strtotime('+4 hour')));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $guide = curl_exec($ch);
  //  echo $guide."<br/><br/><br/><br/>";
    $guide = json_decode($guide,true);
	
    curl_close($ch);
    
    $j=0;

    foreach($guide[relationship_list] as $programvalue){
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, "http://api.cods-dev.ngb.biz/rest/v3/programs/".$programvalue[program_id]."?session=".$_SESSION['user_session_id']);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($ch, CURLOPT_HEADER, 0);
         $programprofile = curl_exec($ch);
         $programprofile = json_decode($programprofile,true);  
         curl_close($ch);

         // $guide[relationship_list][$j][profile]=$programprofile;
		 // $s=$guide[relationship_list][$j][start_time];
		 // $e=$guide[relationship_list][$j][end_time];
		 // $guide[relationship_list][$j][start_time]=date("Y-m-d H:i:s",strtotime("$s -4 hour"));
		 // $guide[relationship_list][$j][end_time]=date("Y-m-d H:i:s",strtotime("$e -4 hour"));
         $j++;
    }
	echo "<strong>This is entry list: </strong>";print_r($guide["entry_list"]);echo "<br/><br/>";
	echo "<strong>This is relationship list: </strong>";print_r($guide["relationship_list"]);echo "<br/><br/>";
	echo "<br/><br/><br/><br/>";
	
        $channels[entry_list][$i][guide]=$guide[relationship_list];
    $i++;
	//print_r($guide);echo "<br/>";
}
$channels = json_encode($channels);
