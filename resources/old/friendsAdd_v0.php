<?php
/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */
require 'facebook-php-sdk/src/facebook.php';
require 'config.php';
session_start();
 $access_token = $_SESSION['access_token'];
//$access_token = htmlspecialchars($_GET["access_token"]);

//Create facebook application instance.
$facebook = new Facebook(array(
  'appId'  => $fb_app_id,
  'secret' => $fb_secret,
  'cookie' => true,
));

//$sent = false;

$user = $facebook->getUser();
        
// Proceed knowing you have a logged in user who's authenticated.
$user_profile = $facebook->api('/me');
$friends_profile = $facebook->api('/me/friends');	
$myFriendList = $facebook->api('/me/friendlists');
$access_token = $facebook->getAccessToken();
$list_name = "dragon_trainers";


// Login or logout url will be needed depending on current user state.
$logoutUrl = $facebook->getLogoutUrl();

?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>demo</title>
    <script type="text/javascript" src="JS/jquery_1.7.2.js"></script>
	<script type="text/javascript" src="JS/idrag.js"></script>
	<script type="text/javascript" src="JS/idrop.js"></script>
	<script type="text/javascript" src="JS/iutil.js"></script>
    <style>
    	ul, li {
    	list-style: none outside none;
   	 	padding: 0;
		}
    	.uki-dataGrid-cell {
    	display: inline-block;
    	padding: 1px;
    	vertical-align: top;
		}
      	.friendList_cell {
    	background: -moz-linear-gradient(center top , #FFFFFF, #F4F4F4) repeat scroll 0 0 transparent;
    	border: 1px solid #DDDDDD;
    	border-radius: 2px 2px 2px 2px;
    	box-shadow: 0 1px 0 rgba(0, 0, 0, 0.2);
    	cursor: move;
    	font: 13px arial,sans-serif;
    	height: 50px;
    	margin: 10px 20px 13px 0;
    	padding: 4px;
    	position: relative;
    	vertical-align: top;
    	width: 138px;
    	word-wrap: break-word;
		}
		.friendList_cell:hover {
    	box-shadow: 0 0 3px 3px rgba(0, 0, 0, 0.3);
		}
		.frientList_person {
    	height: 48px;
   	 	position: absolute;
    	width: 48px;
		}
		.frientList_name {
    	margin: 4px 4px 4px 58px;
		}
		.splitPane-handle{
		background: none repeat scroll 0 0 #FFFFFF;
    	color: #CCCCCC;
    	text-align: center;
    	cursor: row-resize;
    	width: 100%;
    	position: absolute;
   		z-index: 200;
		}
		.splitPane-handle_bar{
		border-top: 1px dashed #CCCCCC;
   	 	left: 0;
    	position: absolute;
    	top: 30px;
    	width: 100%;
    	z-index: 1;
		}
		.splitPane-handle_label{
		background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAAUCAIAAAAP9fodAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBNYWNpbnRvc2giIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MjZBMURENjU5OUZFMTFFMEIwRDJERjUxQUJGMUIyNTMiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MjZBMURENjY5OUZFMTFFMEIwRDJERjUxQUJGMUIyNTMiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDoyNkExREQ2Mzk5RkUxMUUwQjBEMkRGNTFBQkYxQjI1MyIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDoyNkExREQ2NDk5RkUxMUUwQjBEMkRGNTFBQkYxQjI1MyIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PkOimfEAAABcSURBVHjaYvz//z8DEjh79iwDNmBsbIzMZWIgC4xqG1HaGM+cOUOObWiJjRgA1MKEmUwJ6kH4jUidcGVMuLIG/rzDhCdTkZDfsOrEFGQiqAirQUz4nYTL2QABBgCgpRIYXv3PLwAAAABJRU5ErkJggg==") no-repeat scroll 10px 50% white;
    	display: inline-block;
    	line-height: 60px;
    	padding: 0 10px 0 35px;
    	position: relative;
    	z-index: 2;
		}
		.list-item{
		display: block;
		float: left;
		border-width: 0 0 0 1px;
		border-style: none;
		}
		.circle {
    	color: #FFFFFF;
    	height: 125px;
    	margin: 35px;
    	position: relative;
    	width: 125px;
		}
		.circle_disk {
	    -moz-transition-duration: 0.3s;
	    -moz-transition-property: width, height, margin-left, margin-top;
	    border: 1px solid #CCCCCC;
	    border-radius: 200px 200px 200px 200px;
	    height: 124px;
	    left: 50%;
	    margin-left: -62px;
	    margin-top: -62px;
	    position: absolute;
	    top: 50%;
	    width: 124px;
	    z-index: 2;
		}
		.circle_disk {
		}
		.circle_disk {
   		background: -moz-linear-gradient(center top , #EDEDED 0%, #D7D7D7 100%) repeat scroll 0 0 transparent;
		}
		.circle_over .circle_disk {
		background: none repeat scroll 0 0 #E1E1E1;
    	height: 170px;
    	margin-left: -85px;
   	 	margin-top: -85px;
    	width: 170px;
		}
		.circle_over .circleFriend {
    	-moz-transition-delay: 0.1s;
    	opacity: 1;
		}
		.circle_inner {
	    background: none repeat scroll 0 0 #4797CF;
	    border-bottom: 1px solid #FFFFFF;
	    border-radius: 70px 70px 70px 70px;
	    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.3) inset;
	    height: 98px;
	    left: 50%;
	    margin-left: -49px;
	    margin-top: -49px;
	    position: absolute;
	    top: 50%;
	    width: 98px;
	    z-index: 3;
		}
		.circle_name {
	    bottom: 50%;
	    font-size: 13px;
	    position: absolute;
	    text-align: center;
	    width: 98px;
		}
		.circle_number {
	    font-size: 16px;
	    margin-top: 5px;
	    opacity: 0.5;
	    position: absolute;
	    text-align: center;
	    top: 50%;
	    width: 98px;
		}
		.circleFriend {
	    -moz-transition-duration: 0.3s;
	    -moz-transition-property: opacity;
	    border-radius: 30px 30px 30px 30px;
	    height: 30px;
	    left: 50%;
	    opacity: 0;
	    position: absolute;
	    top: 50%;
	    width: 30px;
	    z-index: 4;
		}
    </style>
    <script type="text/javascript">
	$(document).ready(function(){
		$('#splitPane-handle-id').Draggable(
				{
					zIndex: 	1000,
					ghosting:	false,
					opacity: 	0.7,
					axis:		'vertically',
					onDrag:	function(x,y) 
					{	
						$("#friendsWarp").css("height",y);
						$("#friendListWarp").css("top",y+50);
					},
				}
			);
		$(".circle").mouseover(function(){
			$(this).addClass("circle_over");
			})
		$(".circle").mouseout(function(){
			$(this).removeClass("circle_over");
			})
		$('.friendList_cell').Draggable(
				{
					zIndex: 	1000,
					ghosting:	true,
					opacity: 	0.7,
					revert:		true,
				}
			);
		$('.circleFriend').Draggable(
				{
					zIndex: 	1000,
					opacity: 	0.7,
					revert:		true,
				}
			);
		$('.circle').Droppable(
				{
					accept : 'friendList_cell',
					hoverclass:	'circle_over',
					ondrop:	function (drag) 
							{	
								var currentPicNum = $(this).children("img").length;
								var MLeft = [-15,18.5,43,52,43,18.5,-15,-48.5,-73,-82,-73,-48.5];
								var MTop = [-82,-73,-48.5,-15,18.5,43,52,43,18.5,-15,-48.5,-73];
								var currentHTML = $(drag).html();
								currentHTML = currentHTML.replace("frientList_person","circleFriend");
								currentHTML = currentHTML.substring(currentHTML.indexOf("src"),currentHTML.indexOf("picture")+8);
								currentHTML = '<img class="circleFriend" '+currentHTML+' style=" margin-top:'+MTop[currentPicNum]+'px; margin-left:'+MLeft[currentPicNum]+'px;">';
								$(this).append(currentHTML);
								},
					fit: 'pointer'
				}
			);
		$('.friends').Droppable(
				{
					accept : 'circleFriend',
					ondrop:	function (drag) 
							{	
								$(drag).remove();
								},
					fit: 'pointer'
				}
			);
				
	});
	</script>
  </head>
  <body>
<div class="splitPane" style="top: 24px; right: 0px; left: 0px; bottom: 0px; position: absolute;">
	<div id="friendsWarp" style="left: 0px; top: 0px; height: 267px; right: 0px; position: absolute; ">
		<div class="friends" style="top: 0px; right: 0px; left: 10px; bottom: 0px; position: absolute;overflow-y: auto;">
        <?php
            $graph_url= "middle.php?" ."name=".$list_name ."&access_token=" .$access_token ;
            echo '<form enctype="multipart/form-data" action="'.$graph_url .' "method="POST">';
            echo '<ul>';
            foreach ($friends_profile["data"] as $value) {
                echo '<li class="friendList_cell uki-dataGrid-cell">';
                echo '<img class="frientList_person"  src="https://graph.facebook.com/' . $value["id"] . '/picture"/>';
                echo '<div class="frientList_name">'.$value["name"].'</div>';
                echo '</li>';
            }
            echo '</ul>';
            echo '<input type="submit" value="create list"/><br/>';
            echo '</form>';
            ?>
        </div>
	</div>
	<div id="friendListWarp" style="bottom: 0px; right: 0px; top: 317px; left: 0px; position: absolute;">
        <div class="frientList" style="top: 0px; right: 0px; left: 0px; bottom: 0px; position: absolute; overflow-y: auto;">
		<?php
			echo '<ul style="top: 0px; right: 25px; left: 25px; position: absolute; display: block; clear: both;">';
			foreach ($myFriendList["data"] as $value) {
				echo '<li class="list-item">';
				echo '<div class="circle">';
				echo '<div class="circle_disk"></div>';
				echo '<div class="circle_inner">';
				
				$CurrentListfriends = $facebook->api("/".$value["id"]."/members");
				echo '<div class="circle_name">'.$value["name"].'</div><div class="circle_number">'.sizeof($CurrentListfriends["data"]).'</div></div>';
				$counter = 0;
				$marginLeft = array(-15,18.5,43,52,43,18.5,-15,-48.5,-73,-82,-73,-48.5);
				$marginTop = array(-82,-73,-48.5,-15,18.5,43,52,43,18.5,-15,-48.5,-73);
				foreach ($CurrentListfriends["data"] as $value) {
					//find user by id.
					/*$countedUser = $facebook->api('/'.$value['id'],array(
                          'fields' => 'picture',
                          'type'   => 'small'
                      ))*/;
					echo '<img class="circleFriend" src="https://graph.facebook.com/' . $value["id"] . '/picture" style="margin-left: '.$marginLeft[$counter].'px;margin-top:'.$marginTop[$counter].'px;" />';
					$counter++;
					if($counter>11)break;
				}
				echo '</div></li>';
			}
			echo '</ul>';
		?>
		<div id="tempshowcord"></div>
		</div>
	</div>
	<div class="splitPane-handle" id="splitPane-handle-id" style="height: 50px; top: 267px;">
		<div class="splitPane-handle_bar"></div>
		<div class="splitPane-handle_label">Drag people to your facebook lists</div>
	</div>
</div>
  </body>
</html>
