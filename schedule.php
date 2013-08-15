<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>kovue</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/dhtmlxscheduler.css"  media="screen" title="no title" charset="utf-8">
        <link rel="stylesheet" type="text/css" href="resources/pepper-grinder/jquery-ui-1.8.21.custom.css" />
        <link rel="stylesheet" type="text/css" href="css/styles.css"/>
        <link rel="stylesheet" type="text/css" href="css/carousel.css" />
        
        <script src="js/dhtmlxscheduler.js" type="text/javascript" charset="utf-8"></script>
   
	<script src="js/dhtmlxscheduler_minical.js" type="text/javascript" charset="utf-8"></script>
   
<style type="text/css" media="screen">
   html, body{
      margin:0px;
      padding:0px;
      height:100%;
      overflow:hidden;
   }   
</style>

<script type="text/javascript" charset="utf-8">
	function init() {
		scheduler.config.multi_day = true;
		
		scheduler.config.xml_date="%Y-%m-%d %H:%i";
		scheduler.init('scheduler_here',new Date(2013,0,10),"week");
		scheduler.load("events.xml");
	}
	
	function show_minical(){
		if (scheduler.isCalendarVisible())
			scheduler.destroyCalendar();
		else
			scheduler.renderCalendar({
				position:"dhx_minical_icon",
				date:scheduler._date,
				navigation:true,
				handler:function(date,calendar){
					scheduler.setCurrentView(date);
					scheduler.destroyCalendar()
				}
			});
	}
</script>


</head>

    <body onload="init();">
     


     <div id="page-wrap">
	<div class="content_pan">
            <ul id="main-nav">
                <li class="tab1"><a href="index.php"><img src="images/tab_ico1.png" /></a></li>
                <li class="tab2"><a href="#"><img src="images/tab_ico2.png" /></a></li>
                <li class="tab3"><a href="ProgramBoard.php"><img src="images/tab_ico3.png" /></a></li>
                <li class="current"><a href="schedule.php"><img src="images/tab_ico4.png" /></a></li>
                <li class="tab5"><a href="notification.php"><img src="images/tab_ico5.png" /></a></li>
                <li class="tab6"><a href="#"><img src="images/tab_ico6.png" /></a></li>
                <li class="tab7"><a href="#"><img src="images/tab_ico7.png" /></a></li>
            </ul>
            <div class="clear">&nbsp;</div>
            <div class="tab_bottom_pan">
                <div class="tab_l"><h1><span>Program</span> schedule</h1>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
                    
                <div class="clear">&nbsp;</div>
            </div>
            <div id="scheduler_here" class="dhx_cal_container" style='width:818px; height:600px;'>
                      <div class="dhx_cal_navline">
                         <div class="dhx_cal_prev_button">&nbsp;</div>
                         <div class="dhx_cal_next_button">&nbsp;</div>
                         <div class="dhx_cal_today_button"></div>
                         <div class="dhx_cal_date"></div>
                         <div class="dhx_minical_icon" id="dhx_minical_icon" onclick="show_minical()">&nbsp;</div>
                         <div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
                         <div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
                         <div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div>
                      </div>
                      <div class="dhx_cal_header"></div>
                      
                      <div class="dhx_cal_data"></div>
                      
             </div>
            
           
            
        </div>
     </div>
        
    </body>
</html>
