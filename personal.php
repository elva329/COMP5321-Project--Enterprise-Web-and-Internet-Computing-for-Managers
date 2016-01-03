<!DOCTYPE html>
<html>
<head>
	<title>Personal Page</title>
	<link href="css/bootstrap.css" type="text/css" rel="stylesheet" media="all">
	<link href="css/style3.css" type="text/css" rel="stylesheet" media="all">
	<link rel="icon" href="images/favicon.ico">
	<link rel="shortcut icon" href="images/favicon.ico" />
	<link rel="stylesheet" href="css/camera.css">
	<script src="js/jquery.js"></script>
	<script src="js/jquery-migrate-1.2.1.js"></script>
	<script src="js/script.js"></script>
	<script src="js/superfish.js"></script>

	<script src="js/camera.js"></script>
	<!--[if (gt IE 9)|!(IE)]><!-->
	<script>
	$(document).ready(function(){
		jQuery('#camera_wrap').camera({
			loader: false,
			pagination: false ,
			minHeight: '100',
			thumbnails: false,
			height: '27.86458333333333%',
			caption: true,
			navigation: true,
			fx: 'simpleFade'
		});
		$().UItoTop({ easingType: 'easeOutQuart' });
	});
	</script>
	<?php 

	session_start();

	$userId = $_SESSION['userId'];
	$username = $_SESSION['userName'];
    //successfully pass userId
    //echo $userId;

	$dbusername = "root";
	$dbservername = "localhost";
	$dbpassword = "";
	$dbName = "fitBar";


	//start to connect the database
	$mysql_connection = mysql_connect($dbservername,$dbusername,$dbpassword);
		//echo "connection successfully!<br>"
   //select FirBar DB
	mysql_select_db($dbName);

	$sql="SELECT * from userInfo where userid='".$userId."'";

	if ($userinfo = mysql_query($sql)){

		while($row = mysql_fetch_assoc($userinfo)) {

			$img =$row["photoProfie"];

		}
	}

	$sportdata = "SELECT * FROM sportData WHERE userId = '".$_SESSION['userId']."'";
	//echo "userid".$_SESSION['userId'];
	// $result = mysql_query($sql);
	if($result = mysql_query($sportdata)){
		//echo "<br>mysql_query successfully!<br>";
		//combine the link to use the charAPI
		$sum_1=[];
		$count_1=[];
		$avg=[];
		$chart_1="";
		$i=0;
		for($i=1;$i<25;$i++){
			$sum_1[$i]=0;
			$count_1[$i]=0;
			$avg[$i]=0;
		}
		while($row = mysql_fetch_assoc($result)){
			for($i=1;$i<25;$i++){
				$time=intval(substr($row["startTime"], -2));
				if ($time==$i){
					$sum_1[$i]+=$row["step"];
					$count_1[$i]++;
					//echo "step:".$row["step"];
				}
			}
		}
	}

	?>
</head>
<body>
	<!-- content -->
	<div class="content">
		<div class="jumbotron">

			<!-- <img src="images/slide.jpg" style="width:1276px;height: 494px;" /> -->
			<div class="slider_wrapper">
				<div id="camera_wrap" class="">
					<div data-src="images/c4.jpg" >
						<div class="caption fadeIn">
							Tracking your daily steps
						</div>
					</div>
					<div data-src="images/c2.jpg" >
						<div class="caption fadeIn">
							Analysis your fitness
						</div>
					</div>
					<div data-src="images/c3.jpg" >
						<div class="caption fadeIn">
							Keep you fit
						</div>
					</div>
				</div>
			</div>
        </div>
			<div class="collapse navbar-collapse">
				<ul class="nav nav-pills nav-justified ">
					<li><a href="Main_page.html">Logo</a></li>
					<li class="active"><a href="#">HOME</a></li>
					<li ><a href="social.php">FRIENDS</a></li>
					<?php
					echo "<li role='presentation'><a href='#'>Welcome  ".$username."</a></li>";

					?>
					<li><a href="Main_page.html">LOG OUT</a></li>
				</ul> 
			</div>

			<div class="container">

				<div class="col-md-2 pie-charts">
				</div>
				<div class="col-md-6 pie-charts">
					<h2><?php
					echo "<a href='#'>Welcome  ".$username."</a></li>";

					?></h2>
					<div class=" pie-chrt">
						<?php
						for($i=1;$i<25;$i++){
							if($count_1[$i]!=0){
								$avg[$i]=$sum_1[$i]/$count_1[$i];
				       //echo "everage of ".$i." am is :".$sum_1[$i]/$count_1[$i]."<br>";
							}
							if($chart_1==""){
								$chart_1=$chart_1.$avg[$i];
							}else{
								$chart_1=$chart_1.",".$avg[$i];
							}
						}
						echo "<img src='http://chart.apis.google.com/chart?chs=500x400&cht=lc&chxt=x,y&chxr=0,0,24,2|1,0,3000,1000&chds=0,3000&chd=t:".$chart_1."'>";
						?>
						<h3>Analysis:</h3>
						<p >According to the graph above, you are likely <br>to run in the morning from 8am to 10am.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>Remember not to do sports after meals.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
					</div>
				</div>
				<div class="col-md-3 pie-charts">
				</div>
			</div>

			<div class="footer">
				<p> &copy; 2015 Fit Bar. All Rights Reserved | <a href="#">Fit-Bar.com</a></p>
			</div>
		</div>
		<script src="js/jquery.countdown.js"></script>
		<script src="js/cd_config.js"></script>
		<script src="js/modernizr.custom.69142.js"></script>
		<script src="js/bootstrap.js"></script>
	</body>
	</html>
