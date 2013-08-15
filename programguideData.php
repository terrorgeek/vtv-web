<?php
/**
 * Copyright: Next Generation Boardband.Inc. 2012
 * @author:YUNYA SHEN
 * This is the php script that retrieve program schedule from CODS and echo to the clients
 */
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
function sort_programs($array_2d)
{
	for ($i=0; $i <count($array_2d)-1 ; $i++) { 
		for ($j=0; $j <count($array_2d)-$i-1 ; $j++) { 
			if($array_2d[$j]["start_time"]>$array_2d[$j+1]["start_time"])
			{
				$temp=$array_2d[$j];
				$array_2d[$j]=$array_2d[$j+1];
				$array_2d[$j+1]=$temp;
			}
		}
	}
	return $array_2d;
}
$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v3/channels?session=".$_SESSION['user_session_id']."&numentries=12");
curl_setopt($ch, CURLOPT_URL, "http://api.cods-dev.ngb.biz/rest/v3/channels?session=".$_SESSION['user_session_id']."&numentries=100");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$channels = curl_exec($ch);
$channels= json_decode($channels,true);
curl_close($ch);

$i=0;

foreach ($channels[entry_list] as $value){
    $ch = curl_init();
 // curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v3/channels/".$value[id]."/guide?session=".$_SESSION['user_session_id']);
     curl_setopt($ch, CURLOPT_URL, "http://api.cods-dev.ngb.biz/rest/v3/channels/".$value[id]."/guide?session=".$_SESSION['user_session_id']."&start_time=".date('Y-m-d+H:i:s',strtotime('+4 hour'))."&end_time=".date('Y-m-d+H:i:s',strtotime('+10 hour')));
 //   curl_setopt($ch, CURLOPT_URL, "http://api.cods-dev.ngb.biz/rest/v3/channels/".$value[id]."/guide?session=".$_SESSION['user_session_id']."&start_time=".date('Y-m-d+H:i:s',strtotime('+270 minute'))."&end_time=".date('Y-m-d+H:i:s',strtotime('+630 minute')));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $guide = curl_exec($ch);
    $guide = json_decode($guide,true);

	
    curl_close($ch);
    // foreach($guide[relationship_list] as $programvalue){
         // $ch = curl_init();
         // curl_setopt($ch, CURLOPT_URL, "http://api.cods-dev.ngb.biz/rest/v3/programs/".$programvalue[program_id]."?session=".$_SESSION['user_session_id']);
         // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         // curl_setopt($ch, CURLOPT_HEADER, 0);
         // $programprofile = curl_exec($ch);
         // $programprofile = json_decode($programprofile,true);  
         // curl_close($ch);
//          
         // $guide[relationship_list][$j][profile]=$programprofile;
         // $j++;
    // }
   	
	for ($j=0; $j <count($guide[relationship_list]) ; $j++) {
		 $s=$guide[relationship_list][$j][start_time];
		 $e=$guide[relationship_list][$j][end_time];
		 $start=date("Y-m-d H:i:s",strtotime("$s -4 hour"));
		 $end=date("Y-m-d H:i:s",strtotime("$e -4 hour"));
		 
		$channels[entry_list][$i][guide][$j]=array(
			"profile" => array("name"=>$guide[entry_list][$j][name]),
			"id" =>$guide[relationship_list][$j][program_id],
	    	"start_time" => $start,
			"end_time" => $end
	    );
	}
	$channels[entry_list][$i][guide]=sort_programs($channels[entry_list][$i][guide]);
	$i++;
	
	// echo "<strong>This is entry list: </strong>";print_r($channels[entry_list]);echo "<br/><br/>";
	// echo "<strong>This is relationship list: </strong>";print_r($channels[relationship_list]);echo "<br/><br/>";
	// echo "<br/><br/><br/><br/>";
   
//    if($i==0){
//	    $channels[entry_list][$i][guide][0]=array(
//			"profile" => array("name"=>"Swamp Wars"),
//			"id" =>"program_id",
//	    	"start_time" => date("Y-m-d H:i:s",strtotime('0 minute')),
//			"end_time" => date('Y-m-d H:i:s',strtotime('+1 hour'))
//	    );
//	    $channels[entry_list][$i][guide][1]=array(
//	    	"profile" => array("name"=>"Gator Boys: Xtra Bites"),
//	        "id" =>"program_id",
//	        "start_time" => date('Y-m-d H:i:s',strtotime('+0 minute')),
//	        "end_time" => date('Y-m-d H:i:s',strtotime('+1 hour'))
//	    );
//	    $channels[entry_list][$i][guide][2]=array(
//	    	"profile" => array("name"=>"Gator Boys: Xtra Bites"),
//	        "id" =>"program_id",
//	        "start_time" => date('Y-m-d H:i:s',strtotime('+0 minute')),
//	        "end_time" => date('Y-m-d H:i:s',strtotime('+1 hour'))
//	    );
//    }
//    else if($i==1)
//	{
//		$channels[entry_list][$i][guide][0]=array(
//			"profile" => array("name"=>"Swamp Wars"),
//			"id" =>"program_id",
//	    	"start_time" => date("Y-m-d H:i:s",strtotime('0 minute')),
//			"end_time" => date('Y-m-d H:i:s',strtotime('+1 hour'))
//	    );
//	    $channels[entry_list][$i][guide][1]=array(
//	    	"profile" => array("name"=>"Gator Boys: Xtra Bites"),
//	        "id" =>"program_id",
//	        "start_time" => date('Y-m-d H:i:s',strtotime('+0 minute')),
//	        "end_time" => date('Y-m-d H:i:s',strtotime('+1 hour'))
//	    );
//	    $channels[entry_list][$i][guide][2]=array(
//	    	"profile" => array("name"=>"Gator Boys: Xtra Bites"),
//	        "id" =>"program_id",
//	        "start_time" => date('Y-m-d H:i:s',strtotime('+0 minute')),
//	        "end_time" => date('Y-m-d H:i:s',strtotime('+1 hour'))
//	    );
//	}
//    else {
	 //   $guide[relationship_list]["start_time"]=date("Y-m-d H:m:s",strtotime('-4 hours',$guide[relationship_list]["start_time"]));
      //  $channels[entry_list][$i][guide]=$guide[relationship_list];
//    }
}

$channels = json_encode($channels);
echo $channels;
//print_r(get_object_vars(json_decode($channels)));
?>
