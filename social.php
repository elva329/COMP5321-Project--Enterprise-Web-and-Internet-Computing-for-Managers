<!DOCTYPE html>
<html>
<head>
	<title>Social Page</title>
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
	<?php

	session_start();

	$userId=$_SESSION['userId'];
	$username = $_SESSION['userName'];

	$dbusername = "root";
	$dbservername = "localhost";
	$dbpassword = "";
	$dbName = "fitBar";

	$friendName=[];
	$friendsId=[];
	$img=[];
	$i=0;

	$sum_1=[];
	$count_1=[];
	$avg=[];
	$chart_1="";
	$d=0;
	//start to connect the database
	if($mysql_connection = mysql_connect($dbservername,$dbusername,$dbpassword)){
	}
	//select DB
	mysql_select_db($dbName);
	if(isset($_GET['new_friend_id'])){
		$new_friend_id = $_GET['new_friend_id'];

		$sql = "INSERT INTO userRelation (user1Id, user2Id) VALUES ('".$userId."','".$new_friend_id."')";

		if(mysql_query($sql)){
			echo "<script>alert('add friend successfully!');</script>";
		}else{
			echo mysql_error();
		}

	}



	//read relationships of the user.
	$sql_relation = "SELECT * FROM userRelation WHERE user1Id = '".$userId."'";
	if($result_relation = mysql_query($sql_relation)){
		while ($row = mysql_fetch_assoc($result_relation)) {
			$friendId = $row["user2Id"];
	//read firends' name;
			$sql_f = "SELECT * FROM userInfo WHERE id = '".$friendId."'";
			if($result_f = mysql_query($sql_f)){
				while ($row = mysql_fetch_assoc($result_f)) {
					$friendName[$i] = $row["userName"];
					$friendsId[$i]=$friendId;
					$i++;
				}
			}else{
				echo mysql_error();
			}
		}
	}else{
		echo mysql_error();
	}
	//计算每个朋友的每小时平均步数，方便画图
	for ($i=0; $i <count($friendsId) ; $i++) { 
		$sportdata = "SELECT * FROM sportData WHERE userId = '".$friendsId[$i]."'";
		if($result = mysql_query($sportdata)){
			for($d=1;$d<25;$d++){
				$sum_1[$d]=0;
				$count_1[$d]=0;
				$avg[$d]=0;
			}
			while($row = mysql_fetch_assoc($result)){
				for($d=1;$d<25;$d++){
					$time=intval(substr($row["startTime"], -2));
					if ($time == $d){
						$sum_1[$d]+=$row["step"];
						$count_1[$d]++;
					}
				}
			}
		}else{
			echo "wrong!!!!!!!!!!!!!!!!!!!!!";
		}
	}

//================做推荐==================
	$myStep=0;
	$myTime=0;
	$regId=[];
	$j=0;

	$sql_analy = "SELECT * FROM analyzedResult WHERE userId = '".$userId."'";
	if($result_a = mysql_query($sql_analy)){
		//echo "mysql_query successfully!<br>";
		while ($row = mysql_fetch_assoc($result_a)) {
			$myStep=$row["maxStep"];
			$myTime=$row["time"];
			//echo "myStep:".$myStep."<br>";
			//echo "myTime:".$myTime."<br>";
		}
	}else{
		echo mysql_error();
	}

	$sql_all = "SELECT * FROM analyzedResult ";
	if($result_all = mysql_query($sql_all)){

		while ($row = mysql_fetch_assoc($result_all)) {

			if (abs($row["time"]-$myTime)<12&&$row["userId"]!=$userId) {
				$isFriend=0;
				// if ($row["time"]-$myTime<3&&$row["maxStep"]-$myStep<500) {
				for($d=0;$d<count($friendsId);$d++){
					if($friendsId[$d]==$row["userId"]){
						$isFriend=1;
					}
				}
				if($isFriend==0){
					$regId[$j]=$row["userId"];
					$j++;
				}
				
			}
		}
	}else{
		echo mysql_error();
	}
	?>
</head>
<body>
	<!-- content -->
	<div class="content">
		<div class="jumbotron">

			<img src="images/slide.jpg" style="width:1276px;height: 494px;" />
			<!-- <div class="slider_wrapper" >
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
					<div  data-src="images/c3.jpg" >
						<div class="caption fadeIn">
							Keep you fit
						</div>
					</div>
				</div>
			</div> -->
		</div>
		<div class="collapse navbar-collapse">
			<ul class="nav nav-pills nav-justified ">
				<li><a href="main_page.html">Logo</a></li>
				<li ><a href="personal.php">HOME</a></li>
				<li class="active"><a href="#">FRIENDS</a></li>
				<?php
				echo "<li><a href='#'>Welcome ".$username."</a></li>";

				?>
				<li><a href="main_page.html">LOG OUT</a></li>
			</ul> 
		</div>
		<div class="container">
			
			<!--//navigation-->
			<div class="content-btm">
				<!-- <div class="col-md-8 content-grids"> -->
				<div class="col-md-2 menu" >
				</div>
				<div class="col-md-6 menu" >
					<h1>YOUR FRIENDS:</h1>
					<table>
						<?php
						//画图
						for ($i=0; $i <count($friendName); $i++) { 
							echo "<tr>";
							echo "<td>" .$friendName[$i]. "</td>";
							for($d = 1;$d<25;$d++){
								if($count_1[$d] != 0){
									$avg[$d] = $sum_1[$d]/$count_1[$d];
				                        //echo "everage of ".$i." am is :".$sum_1[$i]/$count_1[$i]."<br>";
								}
								if($chart_1==""){
									$chart_1=$chart_1.$avg[$d];
								}else{
									$chart_1=$chart_1.",".$avg[$d];
								}
							}
							echo "<td><img src='http://chart.apis.google.com/chart?chs=250x200&cht=lc&chxt=x,y&chxr=0,0,24,2|1,0,3000,1000&chds=0,3000&chd=t:".$chart_1."'></td>";
							echo "</tr>";
						}
						?>
					</table>
				</div>
				<!-- </div> -->
				<div class="col-md-4 menu8">
					<h2>NEW FRIENDS:</h2>
					<table>
						<?php
						$regname=[];
						$regimg=[];
						//读取推荐用户的用户名
						for ($i=0; $i <count($regId) ; $i++) { 
							$sql2="SELECT * FROM userInfo WHERE id='".$regId[$i]."'";
							if ($result4=mysql_query($sql2)){
								while ($row=mysql_fetch_assoc($result4)) {
									$regname[$i]=$row['userName'];
								}
							}else{
								echo "WWWWWRRRRROOOOONNNNNGGGGG!";
							}
						}
						for ($j=0; $j < count($regname); $j++) { 
							echo "<tr>";
							echo "<td><a href='social.php?new_friend_id=".$regId[$j]."'>".$regname[$j]. "</a></td>";
							echo "</tr>";
						}
						?>
					</table>
				</div>
				<div class="clearfix"></div>
			</div>
			
		
		<div class="footer">
			<p>&copy; 2015 Fit Bar. All Rights Reserved | <a href="#">Fit-Bar.com</a></p>
		</div>
	</div>
</div>
	<!-- Bootstrap core JavaScript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	
	<script src="js/jquery.countdown.js"></script>
		<script src="js/cd_config.js"></script>
		<script src="js/modernizr.custom.69142.js"></script>
		<script src="js/bootstrap.js"></script>

</body>
</html>
