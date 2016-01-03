<?php

$username = "root";
$servername = "localhost";
$password = "";
$dbName = "fitBar";

$userId="6";
	//start to connect the database
$mysql_connection = mysql_connect($servername,$username,$password);
if($mysql_connection){
	echo "connection successfully!<br>";
}
	//select FirBar DB
if(mysql_select_db($dbName)){
	echo "the data has been inserted successfully.<br>";
}else{
	echo mysql_error();
}



//==============analyze================
//初始化
$sum_1=[];
$count_1=[];
$avg=[];
$chart_1="";
$i=0;
$max_step=0;
$max_time=0;
for($i=0;$i<25;$i++){
	$sum_1[$i]=0;
	$count_1[$i]=0;
	$avg[$i]=0;
}

$sql = "SELECT * FROM sportData WHERE userId = '".$userId."'";
echo "userid".$userId."<br>";
	// $result = mysql_query($sql);
if($result = mysql_query($sql)){
	while($row = mysql_fetch_assoc($result)){
		for($i=1;$i<25;$i++){
			$time=intval(substr($row["startTime"], -2));
			if ($time==$i){
				$sum_1[$i]+=$row["step"];
				$count_1[$i]++;
				// echo "step:".$row["step"];
			}
		}
	}
	for($i=1;$i<25;$i++){
		if($count_1[$i]!=0){
			$avg[$i]=$sum_1[$i]/$count_1[$i];
			echo "everage of ".$i." am is :".$sum_1[$i]/$count_1[$i]."<br>";
			//选出运动量最大的时间和运动数据

			if($avg[$i]>$max_step) {
				$max_step=$avg[$i];
				$max_time=$i;
			}

		}
		if($chart_1==""){
			$chart_1=$chart_1.$avg[$i];
		}else{
			$chart_1=$chart_1.",".$avg[$i];
		}
	}
	if($max_step!=0&&$max_time!=0){
		echo "userid".$userId."<br>";
		$result_a = mysql_query("SELECT * FROM analyzedResult WHERE userId = '".$userId."'");
		$row = mysql_fetch_assoc($result_a);
		if(!empty($row)){
			echo "<br>mysql_query successfully!<br>";
			// $sql = "UPDATE analyzedResult (userId, maxStep,time) VALUES ('".$userId."','".$max_step."','".$max_time."')";
			while ($row = mysql_fetch_assoc($result_a)) {
				echo "maxStep:".$row["maxStep"]."<br>";
				echo "time:".$row["time"]."<br>";
			}
			$sql = "UPDATE analyzedResult SET maxStep = '".$max_step."',time = '".$max_time."' WHERE userId = '".$userId."'";
			if(mysql_query($sql)){
				echo "UPDATE successfully.......<br>";
			}else{
				echo mysql_error();
			}
		}else{
			echo mysql_error();
			$sql = "INSERT INTO analyzedResult (userId, maxStep,time) VALUES ('".$userId."','".$max_step."','".$max_time."')";
			if(mysql_query($sql)){
				echo "INSERT successfully.....<br>";
			}else{
				echo mysql_error();
			}

		}

		
	}

}else{
	echo mysql_error();
}


//==============end analyze============


mysql_close($mysql_connection);


?>
