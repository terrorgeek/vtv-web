<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>kovue</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--       <link rel="stylesheet" type="text/css" href="resources/pepper-grinder/jquery-ui-1.8.21.custom.css" /> -->
<!--        <link rel="stylesheet" type="text/css" href="css/style_light.css" > -->
<!--      <script src="js/jquery-1.7.2.js" type="text/javascript"></script> -->
        <link rel="stylesheet" type="text/css" href="css/styles.css"/>
<!--         <link rel="stylesheet" type="text/css" href="css/carousel.css" />
        <script src="js/jquery-ui-1.8.14.custom.min.js" type="text/javascript"></script> -->
<!--         <script src="js/ttw-notification-menu.js" type="text/javascript"></script> -->
        
        
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>    
    <link rel="stylesheet" type="text/css" href="notification_menu/css/style_light.css">
    <script src="notification_menu/js/jquery-ui-1.8.14.custom.min.js" type="text/javascript"></script>
    <script src="notification_menu/js/ttw-notification-menu.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="generate_notification_form/css/style.css">
    <link rel="stylesheet" type="text/css" href="generate_notification_form/css/uniform.css">
    <script type="text/javascript" src="generate_notification_form/js/jquery.tools.js"></script>
    <script type="text/javascript" src="generate_notification_form/js/jquery.uniform.min.js"></script>
   <script>
   	$(document).ready(function(){
   		var notifications = new $.ttwNotificationMenu({
        notificationList:{
            anchor:'item',
            offset:'0 15'
        }
        });

notifications.initMenu({
        requests:'#requests',
        reminder:'#reminder',
        notifications:'#notifications'
});	

setInterval(startRequest,1000);
function startRequest()
{
        $.getJSON("notificationFetchCenter.php?start_time="+$("#last_pointoftime").val(),function(result){
                $("#last_pointoftime").val(result.start_time);
                $.each(result.data, function(i, field){
                	var options=null;
                        if(field.type == 'reminder'){
                        	// notifications.createNotification({
                                // category:'reminder',
                                // message: field.message
                            // });	
                            options = {
                                        category:'reminder',
                                        message: field.message
                                    };
                        }
                        if(field.type == 'requests'){
                                 options = {
                                        category:'requests',
                                        message: field.message
                                    };		
                        }
                        if(field.type == 'notifications'){
                                 options = {
                                        category:'notifications',
                                        message: field.message
                                    };		
                        }
//                        if(field.icon != null){
//                                options.icon = field.icon;
//                        }
//                        else{
                                options.icon = "images/icon.png";
                        //}
                        notifications.createNotification(options);
                });
        });
}
   	});
   </script>
</head>
    <body>
     <div id="page-wrap">
	<div class="content_pan">
            <ul id="main-nav">
                <li class="tab1"><a href="index.php"><img src="images/tab_ico1.png" /></a></li>
                <li class="tab2"><a href="#"><img src="images/tab_ico2.png" /></a></li>
                <li class="tab3"><a href="ProgramBoard.php"><img src="images/tab_ico3.png" /></a></li>
                <li class="tab4"><a href="schedule.php"><img src="images/tab_ico4.png" /></a></li>
                <li class="current"><a href="notification.php"><img src="images/tab_ico5.png" /></a></li>
                <li class="tab6"><a href="#"><img src="images/tab_ico6.png" /></a></li>
                <li class="tab7"><a href="#"><img src="images/tab_ico7.png" /></a></li>
            </ul>
            <div class="clear">&nbsp;</div>
            <div class="tab_bottom_pan">
                <div class="tab_l"><h1><span>Notication</span> reminder</h1>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>

                <div class="clear">&nbsp;</div>
            </div>
            
              <ul class="ttw-notification-menu">
                <li id="requests" class="notification-menu-item first-item"><a>Requests</a></li>
                <li id="reminder" class="notification-menu-item"><a>Reminder</a></li>
                <li id="notifications" class="notification-menu-item last-item"><a>Notifications</a></li>
                <input id="last_pointoftime" type="hidden" value=""/>
            </ul>
            <!-- <center><iframe scrolling="no" src="resources/notification_resources/demo/index.php" style="border:0px;height: 400px;width: 800px;"></iframe></center> -->
     </div>
     </div>

    </body>
</html>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
