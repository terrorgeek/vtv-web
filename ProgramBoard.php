<?php
/**
 * Copyright: Next Generation Boardband.Inc. 2012
 * @author: Fan Zhang, YUNYA SHEN
 * This is the program schedule page. User will be authenticated here. If fail, redirect back to login.
 * If success, the JS will draw the program schedule and the friendslist.
 * Data about schedule and friendslist are obtained by Ajax request to Apps, Apps will retrieve the data from CODS
 * This is the most important page.
 
 */

require 'facebook-php-sdk/src/facebook.php';
require 'config.php';
session_start();
date_default_timezone_set('US/Eastern');
require_once 'date.php';
$access_token = $_SESSION['access_token'];
$friends_ids = $_POST['fid'];
$friends_names = $_POST['fname'];
//echo date("Y-m-d H:i:s")."------<br/>";
//Create facebook application instance.
$facebook = new Facebook(array(
  'appId'  => $fb_app_id,
  'secret' => $fb_secret,
  'cookie' => true,
));

//check existing friendslists find the friendslist called 'kovue' or create it
 $friendListSet = $facebook->api('/me/friendlists');
$friend_list_id = null;
$flag = false;
foreach ($friendListSet['data'] as $friendlist){
	if($friendlist['name']=='kovue'){
		$flag = true;
		$friend_list_id = $friendlist['id'];
	}
}
$counter = count($friends_ids);
//if(!$flag){
//	$kovue = $facebook->api('/me/friendlists','POST',array(
//	    'name' => 'kovue',
//	    'access_token' => $access_token,
//	    'privacy' => array('value' => 'EVERYONE')
//	));
//	$friend_list_id = $kovue['id'];	
//	foreach ($friends_ids as $key => $value){
//		$ids = $ids.','.$friends_ids[$key];
//	}
//	$ids = substr($ids, 1);
//	$facebook->api('/'.$friend_list_id .'/members?members='.$ids,'POST',array('access_token' => $access_token,'privacy' => array('value' => 'EVERYONE')));		
//}


echo "<br>";
//echo $_POST['email'];
//login user:
$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v3/login?login=yshen@ngb.biz&password=" . md5("ngbweb"));
//curl_setopt($ch, CURLOPT_URL, "http://api.cods-dev.ngb.biz/rest/v3/login?login=yshen@ngb.biz&password=".md5("ngbweb"));
curl_setopt($ch, CURLOPT_URL, "http://api.cods-dev.ngb.biz/rest/v3/login?login=bob.smith@cods.pyctex.net&password=103891baca2751a856b094db796e3fee");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$userCred = curl_exec($ch);
$userCred = json_decode($userCred, true);
curl_close($ch);
print_r($userCreder);
//print_r($userCred);
$_SESSION['user_session_id'] = $userCred['id'];
//retrieve profile
$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v3/profile?session=" . $userCred['id']);
curl_setopt($ch, CURLOPT_URL, "http://api.cods-dev.ngb.biz/rest/v3/profile?session=".$userCred['id']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$Profile = curl_exec($ch);
$Profile = json_decode($Profile, true);
curl_close($ch);
$_SESSION['user_profile_id'] = $Profile['id'];


//obtain the admin session
$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL, "http://api.cods.pyctex.net/rest/v3/login?login=APIuser&password=c72381ccfd9e53616d5fd76eceaca638");
//curl_setopt($ch, CURLOPT_URL, "http://api.cods-dev.ngb.biz/rest/v3/login?login=yshen@ngb.biz&password=".md5("ngbweb"));
curl_setopt($ch, CURLOPT_URL, "http://api.cods-dev.ngb.biz/rest/v3/login?login=bob.smith@cods.pyctex.net&password=103891baca2751a856b094db796e3fee");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$admin_session = curl_exec($ch);
$admin_session = json_decode($admin_session, true);
curl_close($ch);

//print_r($admin_session);

$_SESSION['admin_session_id'] = $admin_session['id'];



?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>kovue</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <link rel="stylesheet" type="text/css" href="resources/pepper-grinder/jquery-ui-1.8.21.custom.css" />
        <link rel="stylesheet" type="text/css" href="css/styles.css"/>
        <link rel="stylesheet" type="text/css" href="css/carousel.css" />
        <link rel="stylesheet" href="js/jNotify-master/jquery/jNotify.jquery.css" type="text/css" />
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js" type="text/javascript" ></script>
            <script src="js/jquery-1.7.2.js" type="text/javascript"></script>
            <script src="js/ui/jquery-ui-1.8.21.custom.js" type="text/javascript"></script>
            
            <script src="js/custom-form-elements.js"></script>
            <script src="js/jquery.jcarousel.min.js" type="text/javascript"></script>
            <script src="js/jquery.mousewheel.min.js"type="text/javascript"></script>
            <script src="js/slickcustomscroll.js"></script>
            <script src="js/hovertip.js" type="text/javascript" ></script>
            
            <script type="text/javascript" src="js/jNotify-master/jquery/jNotify.jquery.min.js"></script>
            <script type="text/javascript" src="js/jNotify-master/jquery/jNotify.jquery.js"></script>
            <script >
     var margin_left_flag=0;
                jQuery(document).ready(function() {
                    jQuery('#mycarousel').jcarousel(); 
                });
                function change_channel(channel)
                {
                    var channel_id=channel.id;
                  //  alert(channel_id);
                    $.ajax({
                      type: "POST",
                      url: "change_channel.php",
                      data: { requested_channel_id: channel_id },
                      success:function( msg ) {
                      	//alert(msg);
                        // alert( "Data Saved: " + msg );
                        // jSuccess('Congratulations, channel change success !!',
                        // {
		                   // autoHide : true, // added in v2.0
		                   // clickOverlay : false, // added in v2.0
		                   // MinWidth : 550,
	                  	   // TimeShown : 1000,
		                   // ShowTimeEffect : 200,
		                   // HideTimeEffect : 200,
		                   // LongTrip :20,
		                   // HorizontalPosition : 'center',
		                   // VerticalPosition : 'center',
		                   // ShowOverlay : true,
   		                   // ColorOverlay : '#000',
		                   // OpacityOverlay : 0.3,
		                   // onClosed : function(){ // added in v2.0
		                   	// },
		                   // onCompleted : function(){ // added in v2.0
		                    // }
                       // })
                      }
                     
                    });
                }
                 function hideScreen() {
                var Element = document.getElementById('lockScreen');
                var Elements = document.getElementById('Screen');
                Element.style.display = 'none';
                Elements.style.display = '';
                }
    
                $(document).ready(function(){	
                    $("#fade").fadeIn(30000);
                    $( "div[rel='scrollcontent1']" ).customscroll( { direction: "vertical" } );
                    $( "div[rel='scrollcontent2']" ).customscroll( { direction: "vertical" } );
                    $( "div[rel='scrollcontent3']" ).customscroll( { direction: "vertical" } );
                   
                    window.setTimeout(hovertipInit, 1);
        
//                    var friendsInfoString = "<ul class='listing_r'>";
                    //fetch friends data, fetch data from cods, the returned data is JSON
//                    $.getJSON("friendsData.php",function(result){
//                        $.each(result.entry_list, function(i, field){
//                            if(field.sip_status == "offline"){
//                                //if the friends is offline, gray the avatar, use the 'friendOffline' class.
//                                friendsInfoString += "<li ><img src=https://graph.facebook.com/"+field.facebook_id+"/picture /><span><span>"+field.name+"</span></span>"
//                            }
//                            else{
//                                friendsInfoString += "<li ><img src=https://graph.facebook.com/"+field.facebook_id+"/picture /><span><span>"+field.name+"</span></span>"
//                            }
//                            friendsInfoString +="<span style='display:none'>"+field.id+"</span></li>"
//                        }); 
//                        friendsInfoString += "<li><img src='images/thumb1.jpg' /> <span><span>Daniele de Agelis</span></span></li>";
//                        friendsInfoString += "<li><img src='images/thumb2.jpg' /> <span><span>Svieltana Andrusenko</span></span></li>";
//                        friendsInfoString += "<li><img src='images/thumb3.jpg' /> <span><span>Daniele de Agelis</span></span></li>";
//                        friendsInfoString += "<li><img src='images/thumb4.jpg' /> <span><span>Daniele de Agelis</span></span></li>";
//                        friendsInfoString += "<li><img src='images/thumb3.jpg' /> <span><span>Svieltana Andrusenko</span></span></li>";
//                        friendsInfoString += "<li><img src='images/thumb2.jpg' /> <span><span>Daniele de Agelis</span></span></li>";
//                        friendsInfoString += "<li><img src='images/thumb1.jpg' /> <span><span>Daniele de Agelis</span></span></li>";
//                        friendsInfoString += "<li><img src='images/thumb2.jpg' /> <span><span>Svieltana Andrusenko</span></span></li>";
//                        friendsInfoString += "<li><img src='images/thumb3.jpg' /> <span><span>Daniele de Agelis</span></span></li>";
//                        friendsInfoString += "<li><img src='images/thumb4.jpg' /> <span><span>Daniele de Agelis</span></span></li>";
//                        friendsInfoString += "<li><img src='images/thumb3.jpg' /> <span><span>Svieltana Andrusenko</span></span></li>";
//                        friendsInfoString += "<li><img src='images/thumb2.jpg' /> <span><span>Daniele de Agelis</span></span></li>";	
//                        friendsInfoString += "<li><img src='images/thumb1.jpg' /> <span><span>Daniele de Agelis</span></span></li>";
//                        friendsInfoString += "<li><img src='images/thumb2.jpg' /> <span><span>Svieltana Andrusenko</span></span></li>";
//                        friendsInfoString += "<li><img src='images/thumb3.jpg' /> <span><span>Daniele de Agelis</span></span></li>";
//                        friendsInfoString += "<li><img src='images/thumb4.jpg' /> <span><span>Daniele de Agelis</span></span></li>";
//                        friendsInfoString += "<li><img src='images/thumb3.jpg' /> <span><span>Svieltana Andrusenko</span></span></li>";
//                        friendsInfoString += "<li><img src='images/thumb2.jpg' /> <span><span>Daniele de Agelis</span></span></li>";	
//                        friendsInfoString += "<li><img src='images/thumb3.jpg' /> <span><span>Svieltana Andrusenko</span></span></li>";
//                        friendsInfoString += "<li><img src='images/thumb2.jpg' /> <span><span>Daniele de Agelis</span></span></li>";
//                        friendsInfoString += "</ul>";
//                        document.getElementById("friends").innerHTML=friendsInfoString;
//                    });
                       
                
                    //fetch program schedule data from CODS, data returned in JSON
                    //draw the program panel. All functionality related to Program schedule panel are included:draw program board, draggable, like and unlike button and so on
                    //all jquery events registeration happens after the ajax request, which means we fill the content first and then register the jquery events
                    
                    $.getJSON("programguideData.php",function(result){	
                        var datetoday=new Date();
                        var requestTime = datetoday.getTime();
                        //draw the timeline. We calculate the size of the box. For a program last for 1 hour, the box is 180px width
                        //the height is a fixed value.
                     //   var timelineLeft = (60-datetoday.getMinutes())*6+156;
                        var timelineLeft = (60-39)*6+156;
                        $("#timeline").css("padding-left",timelineLeft+"px");
                        var timelineString = "";
                        for(var i=0;i<=24;i++){
                            timelineString+="<span class='timelineSpan'><b>"+(datetoday.getHours()+i)%24+":00</b></span>";
                            timelineString+="<span class='timelineSpan'><b>"+(datetoday.getHours()+i)%24+":30</b></span>";
                        }
                        $("#timeline").append(timelineString);
                        //draw the programs.  We calculate the size of the box. For a program last for 1 hour, the box is 180px width
                        //the height is a fixed value.
                        var programnum=1;
                        $.each(result.entry_list, function(i, field){
                        $(".mainDivLeft").append("<div class='channelBlock'> 0"+programnum+" "+field.name+"<br><img style='left:30;position:relative;' width='80' src='images/"+programnum+".png' ></div>");
                        programnum++;
			            var programString = "<div class='scroll-content'>";	
                        $.each(field.guide,function(j, program){
                        	    //var initDate=new Date(new Date(program.start_time)-4*60*60*1000);
                                var begintime_ms = Date.parse(new Date(program.start_time.replace(/-/g, "/")));
                              //  alert(program.start_time);
                               // var begintime_ms = Date.parse(new Date(initDate.replace(/-/g, "/")));
                                var endtime_ms = Date.parse(new Date(program.end_time.replace(/-/g, "/")));
                                var cellWidth = 6*Math.floor((endtime_ms-begintime_ms)/60000)-6;
                                //var cellLeft = Math.floor((begintime_ms - requestTime )/60000)+45;
                                var cellLeft=0;
                             //   if(j==0)
                            //    {
                            	
                            	var date=new Date();
                            	var today="<?php echo $today;?>";
                                var current_hour=Date.parse(new Date(today));
                           //    alert(date.getFullYear()+"/"+(date.getMonth()+1)+"/"+(date.getDay())+" "+date.getHours()+":00:00");
                        //       alert(((begintime_ms - current_hour)/60000)+12);
                                
                            	
                                  //	 cellLeft = Math.floor((begintime_ms - requestTime )/60000)+30;
                                  	// alert(((begintime_ms - current_hour )/60000)+30);
                                  	 cellLeft = Math.floor((begintime_ms - current_hour )/60000)+2;
                                  	// margin_left_flag=cellLeft;
                                // }
                                // else
                                    // cellLeft=0;
                                // else if(j==1)
                                   // cellLeft=margin_left_flag+10;                                
                                var displayST = program.start_time.split(" ");
                                var displayET = program.end_time.split(" ");
                                var friendcountwidth=cellWidth-20;
                            
                           
                                //calculate the position based on program's start and ending time.                        
			var channel_id=field.id;
            programString += "<div id='"+channel_id+"' onclick='javascript:change_channel(this);' class='scroll-content-item ui-widget-header ui-droppable' \n\
                                style='left:"+cellLeft*6+"px; \n\
                                position:absolute; \n\
                                width:"+cellWidth+"px;\n\
                            '>"+"<div class='scroll-content-item-upper'>\n\
                                    <input class='guide_id' type='hidden' value='"+program.id+"' />\n\
                                    <p class='programname' ><b><a href='#' class='wocao' id='"+channel_id+"' onclick='javascript:change_channel(this);'>"+program.profile.name+"</a></b>"+program.start_time+"</p>\n\
                                <img class='favIcon'  src='images/onebit_46.png'>\n\
                                <img class='friendsIcon'  style='float:right;' src='images/icon_01.png'>";
                                
                        if(true){
                            programString += "<span style='color:white;position:absolute;top:24;left:"+friendcountwidth+";'  class='friendsCounter'>1</span> \n\
                                <div class='friendsDiv'><p>Currently have no friends here</p></div>";
                        }
                        else{
                            programString += "<span class='friendsCounter'>"+program.profiles.result_count+"</span><div class='friendsDiv'>";

                            $.each(program.profiles.entry_list,function(k, friend){
                                programString += "<li class='friendList_cell' style='-moz-user-select: none;'><img class='frientList_person' src='https://graph.facebook.com/"+friend.facebook_id+"/picture'><div class='frientList_name'>"+friend.last_name+friend.frist_name+"</div></li>";
                            });
                        }

                        programString +="</div></div>";

                    });  
                    programString+="<div class='scroll-content-item ui-widget-header' style='float:right; width:30px;'>>></div> </div>";
                
                    // alert(programString);
                    $(".mainDivMIddle .scroll-pane").append(programString);
                });
                
                //--------------------------all below are slider effects-------------------------------------------------
                var scrollPane = $( ".scroll-pane" ),
                scrollContent = $( ".scroll-content" );

                //build slider
                var scrollbar = $( ".scroll-bar" ).slider({
                    slide: function( event, ui ) {
                        $("#friendsWindow").hide();
                        if ( scrollContent.width() > scrollPane.width() ) {
                            scrollContent.css( "margin-left", Math.round(
                            ui.value / 100 * ( scrollPane.width() - scrollContent.width() )
                        ) + "px" );
                        } else {
                            scrollContent.css( "margin-left", 0 );
                        }
                    }
                });

                //append icon to handle
                var handleHelper = scrollbar.find( ".ui-slider-handle" )
                .mousedown(function() {
                    scrollbar.width( handleHelper.width() );
                })
                .mouseup(function() {
                    scrollbar.width( "100%" );
                })
                .append( "<span class='ui-icon ui-icon-grip-dotted-vertical'></span>" )
                .wrap( "<div class='ui-handle-helper-parent'></div>" ).parent();
                //change overflow to hidden now that slider handles the scrolling
                scrollPane.css( "overflow", "hidden" );
                //size scrollbar and handle proportionally to scroll distance
                function sizeScrollbar() {
                    var remainder = scrollContent.width() - scrollPane.width();
                    var proportion = remainder / scrollContent.width();
                    var handleSize = scrollPane.width() - ( proportion * scrollPane.width() );
                    scrollbar.find( ".ui-slider-handle" ).css({
                        width: handleSize,
                        background: "transparent",
                        border: "2px solid #654B24",
                        cursor: "move",
                        "margin-left": -handleSize / 2

                    });
                    handleHelper.width( "" ).width( scrollbar.width() - handleSize );
                }
                //reset slider value based on scroll content position
                function resetValue() {
                    var remainder = scrollPane.width() - scrollContent.width();
                    var leftVal = scrollContent.css( "margin-left" ) === "auto" ? 0 :
                        parseInt( scrollContent.css( "margin-left" ) );
                    var percentage = Math.round( leftVal / remainder * 100 );
                    scrollbar.slider( "value", percentage );
                }
                //if the slider is 100% and window gets larger, reveal content
                function reflowContent() {
                    var showing = scrollContent.width() + parseInt( scrollContent.css( "margin-left" ), 10 );
                    var gap = scrollPane.width() - showing;
                    if ( gap > 0 ) {
                        scrollContent.css( "margin-left", parseInt( scrollContent.css( "margin-left" ), 10 ) + gap );
                    }
                }
                //change handle position on window resize
                $( window ).resize(function() {
                    resetValue();
                    sizeScrollbar();
                    reflowContent();
                });
                //init scrollbar size
                setTimeout( sizeScrollbar, 10 );
                //--------------------------all above are slider effects-------------------------------------------------

                //--------------------------all below are draggable and droppable-------------------------------------------------
                $(".listing_r li").draggable({helper:"clone",
                });

                $(".scroll-content-item").droppable({
                    hoverClass: "ui-state-active",
                    drop: function( event, ui ) {
                        //if there is no friends in this program, remove the <p> tag first
                        $(this).find(".friendsDiv").find("p").remove();
                        //accept the dropped friends, check the dulplication first
                        if(($(this).find(".friendsDiv").html().indexOf(ui.draggable.context.innerHTML.substring(ui.draggable.context.innerHTML.indexOf("<span"),ui.draggable.context.innerHTML.indexOf("</span>")))) == -1 ){
                            $(this).find(".friendsDiv").append(ui.draggable.clone()).children("li").draggable({  
                                scroll: true,
                                helper:"clone",
                                zIndex:1000 });
                            //updata user's action to CODS:
                            //obtain friends profile_id first
                            var to_id = ui.draggable.context.innerHTML.substring(ui.draggable.context.innerHTML.indexOf("</span>")-36,ui.draggable.context.innerHTML.indexOf("</span>"));
                            //obtain program_id
                            var guide_id = $(this).find(".guide_id").val();
                            //send request to CODS, mark the notification as 'readed'
                            $.getJSON("requestProcessCenter.php?type=4&&guide_id="+guide_id+"&&to_id="+to_id, function (result, textStatus){
                                //actions to be done.
                                alert(result);
                            });

                            //change the icon from gray to normal. Since somefriends may not online, they icon are gray	
                            $(this).find(".friendsDiv li").removeClass("friendOffline");	
                            $(this).find(".friendsCounter").html((parseInt($(this).find(".friendsCounter").html())+1));	
                            //pop up the icon
                            var eleOffset = $(this).offset();
                            $("#infoWindow2").show();
                            $("#infoWindow2").css("left",(eleOffset.left+10)+"px").css("top",(eleOffset.top-10)+"px").animate({top:(eleOffset.top-30)+"px"}, "slow");
                            setTimeout(function(){$("#infoWindow2").hide();},1500);
                        }
                        else{
                            //show the warning that the friend is already in the list.
                            var eleOffset = $(this).offset();
                            $("#infoWindow3").css("left",(eleOffset.left-((210-$(this).width())/2))+"px").css("top",(eleOffset.top+100)+"px").show();
                            setTimeout(function(){$("#infoWindow3").fadeOut('slow');},600);		
                        }
                    }
                });

                //--------------------------all above are draggable and droppable-------------------------------------------------

                //--------------------------others functionality-------------------------------------------------
                //like/dislike icon
                $(".favIcon").click(function(){
                    var eleOffset = $(this).offset();

                    if($(this).attr("src")=="images/onebit_46.png"){
                        //add code about like
                        $(this).attr("src","images/t_up.png");
                        $("#infoWindow1").html(" ").html("Like").css("left",(eleOffset.left-10)+"px").css("top",(eleOffset.top-20)+"px").show();
                        setTimeout(function(){$("#infoWindow1").fadeOut('slow');},600);

                    }
                    else if($(this).attr("src")=="images/t_up.png"){
                        //add code about dislike
                        $(this).attr("src","images/t_down.png");
                        $("#infoWindow1").html(" ").html("disLike").css("left",(eleOffset.left-10)+"px").css("top",(eleOffset.top-20)+"px").show();
                        setTimeout(function(){$("#infoWindow1").fadeOut('slow');},600);

                    }
                    else{
                        //add code about removing favorite
                        $(this).attr("src","images/onebit_46.png");
                        $("#infoWindow1").html(" ").html("unlike").css("left",(eleOffset.left-10)+"px").css("top",(eleOffset.top-20)+"px").show();
                        setTimeout(function(){$("#infoWindow1").fadeOut('slow');},600);
                    }
                });

                 $(".programname").click(function(){
                   var eleOffset = $(this).offset();
                   $("#programdetail")
                   .fadeIn('slow')

                    .css("left",(eleOffset.left-50)+"px")
                    .css("top",eleOffset.top+"px")



                });
                $("#WindowClose").click(function(){
                    $("#programdetail").fadeOut('slow')
                })




                //click the friends icon, show the friends related to this program.
                //put all text into a div called 'friendsWindow' and show this window.
                $(".friendsIcon").click(function(){
                    var eleOffset = $(this).offset();
                    $("#friendsWindow")
                    .fadeIn('slow')
                    .css("left",(eleOffset.left-100)+"px")
                    .css("top",eleOffset.top+"px")

                    .html("")
                    .append($(this).nextAll(".friendsDiv").clone())
                    /*.find("li").draggable({  
                        scroll: true,
                        helper:"clone",
                        zIndex:1000 });*/
                   setTimeout(function(){$("#friendsWindow").fadeOut('slow');},2000); 
                });
                $("#friendsWindowClose").click(function(){
                    $("#friendsWindow").hide('slide',1000)

                })

            });


        });
	
            </script>

    </head>

    <body>
     


     <div id="page-wrap">
	<div class="content_pan">
            <ul id="main-nav">
                <li class="tab1"><a href="index.php"><img src="images/tab_ico1.png" /></a></li>
                <li class="tab2"><a href="#"><img src="images/tab_ico2.png" /></a></li>
                <li class="current"><a href="ProgramBoard.php"><img src="images/tab_ico3.png" /></a></li>
                <li class="tab4"><a href="schedule.php"><img src="images/tab_ico4.png" /></a></li>
                <li class="tab5"><a href="notification.php"><img src="images/tab_ico5.png" /></a></li>
                <li class="tab6"><a href="#"><img src="images/tab_ico6.png" /></a></li>
                <li class="tab7"><a href="#"><img src="images/tab_ico7.png" /></a></li>
            </ul>
            <div class="clear">&nbsp;</div>
            <div class="tab_bottom_pan">
                <div class="tab_l"><h1><span>Program</span> guide</h1>
	                <p></p>
                </div>

                <div class="clear">&nbsp;</div>
            </div>
           
             <div style="position: relative;top:300px; text-align:center ;width:851px; height: 800px;float:right" id="lockScreen">
                 
                        <form name=loading>
                                <td >
                                        <p>
                                                <font size=46 color=gray>Loading,please wait.......</font>
                                        </p>
                                        <p>
                                                <input type=text name=chart size=46
                                                        style="font-family:Arial; 
                                                                font-weight:bolder; color:gray;
        background-color:white; padding:0px; border-style:none;">
                                                <br> 
                                                    <input type=text name=percent size=46
                                                        style="font-family:Arial; 
        color:gray; text-align:center; 
        border-width:medium; border-style:none;">
                                                <script>
                                                        var bar = 0;
                                                        var line = "||";
                                                        var amount = "||";
                                                     //   count();
                                                        function count() {
                                                                bar = bar + 2;
                                                                amount = amount + line;
                                                                document.loading.chart.value = amount;
                                                                document.loading.percent.value = bar + "%";
                                                                if (bar < 99) {
                                                                        setTimeout("count()", 200);
                                                                } else {
                                                                        hideScreen();
                                                                }
                                                        }
                                                </script>
                                        </p>
                                </td>
                        </form>
                
            </div>
            
            
            
            <div id="Screen" style="display: none;"> 
                
            <div style="width:681px;height: 21px;float: left;overflow:hidden;">
                <div class="scroll-bar-wrap ui-widget-content ui-corner-bottom">

                    <div class="scroll-content" id="timeline"></div>
                    <div class="scroll-bar"></div>
                </div>   
            </div>

            <div class="mainDivMIddle" >
                <div class="mainDivLeft"></div>
                <div class="scroll-pane ui-widget ui-widget-header ui-corner-top" >
                </div>
            </div>
                
                
             <div class="mainDivRight" >

                <div id="friends" >
	                <ul class='listing_r'>
	                	<?php 
	                	    if(count($friends_ids)==0)
							{
						?>
						<script>
						function load(){
							var LockScreen=document.getElementById("lockScreen");
							var Screen=document.getElementById("Screen");
							LockScreen.style.display="none";
							Screen.style.display="";
						}
						setTimeout(load,4000);
						</script>
						<?php
						    }
							else
							{
						?>
						<script>
							count();
						</script>
						<?php
								foreach ($friends_ids as $key => $value) {
					   			   echo '<li ><img src="https://graph.facebook.com/'.$value.'/picture"/><span><span>'.$friends_names[$key].'</span></span></li>';
							    }
							}
						?>
	                </ul>
                </div>

            </div>
                
            </div>
            
            
            


            <div class="clear">&nbsp;</div>
            <div id="friendsWindow"><img id='friendsWindowClose' src='images/onebit_33.png' /></div>
            <div id="infoWindow1">Add favorite</div>
            <div id="infoWindow2"><img src="images/onebit_31.png" /></div>
            <div id="infoWindow3">the friend is already in the list</div>
            <!-- <div id="programdetail">
                <img id="WindowClose" src="images/onebit_33.png" />
                    <img src='images/thumb5.jpg' class='fl' />
                    <span class='fl'> <h3>American Dad</h3>
                  
                        Wed, Sun 19:00-21:00</span> 
                    <p>The random escapades of Stan Smith, an extreme right wing CIA agent dealing with family life and keeping America safe, all in the most absurdist way possible.<br><br>
                        <strong>Creators:</strong> <a href='#'>Seth MacFarlane, Mike Barker, Matt Weitzman</a> <strong>Stars:</strong> <a href='#'>Wendy Schaal, Dee Bradley Baker and Scott Grimes?</a>
                    </p>
                    <p><a href='#' class='btn'>See Details</a></p>
              
            </div> -->
        </div>
     </div>

    </body>
</html>
