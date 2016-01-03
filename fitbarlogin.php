<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
</head>
<body>

	<?php
	session_start();
	if($_SERVER['REQUEST_METHOD']=="POST"){
	 	$email = $_POST['email'];
	 	$password = $_POST['password'];
	}else{
		echo "wrong heer!";
	}
	//connect the DB
	$dbusername = "root";
	$dbservername = "localhost";
	$dbpassword = "";
	$dbName = "fitBar";
	$temp = "XXXX";


	//start to connect the database
	if($mysql_connection = mysql_connect($dbservername,$dbusername,$dbpassword)){
		//echo "connection successfully!<br>";
	}else{
		echo "connection wrong<br>";
	}
	//select FirBar DB
	mysql_select_db($dbName);

	$sql = "SELECT * FROM userInfo WHERE email = '".$email."'";

	// $result = mysql_query($sql);
	if($result = mysql_query($sql)){
		//echo "mysql_query successfully!<br>";
	}else{
		echo mysql_error();
	}
	//echo "@@@result:".$result."</br>";
	if ($result = mysql_query($sql)){

	while($row = mysql_fetch_assoc($result)){

		$temp = $row["password"];
		//echo "temp:".$row["password"]."</br>";

	}

}else{
	// echo "Xxxxxxxxxxxxxxxx";
}

	if($temp == $password){
		// echo "<h1>Successfully Login</h1><br>";
		// echo "<a href = 'personal.php'>Go to My Personal Page</a>";
		/*set the session variables*/

		//store two session variables to transfer to the personal page.
		// $result_two = mysql_query("SELECT * FROM userInfor WHERE email = $email");
		if($result_two = mysql_query("SELECT * FROM userInfo WHERE email = '".$email."'")){
			//echo "<br>mysql_query successfully!<br>";
		}else{
			echo mysql_error();
		}
		

		while($row = mysql_fetch_assoc($result_two)){
			$_SESSION['userName'] = $row['userName'];
			$_SESSION['userId'] = $row['id'];
			//echo "session:".$_SESSION['userName']."</br>";
			//echo "session:".$_SESSION['userId']."</br>";
		}

		// $result_three = mysql_query("SELECT * FROM userInfor WHERE email = $email");
		// if($result_three = mysql_query("SELECT * FROM userInfo WHERE email = '".$email."'")){
		// 	echo "mysql_query successfully!<br>";
		// }else{
		// 	echo mysql_error();
		// }
		// while($row = mysql_fetch_assoc($result_three)){
		// 	$_SESSION['userId'] = $row['userId'];
		// }
	}else{
		echo "Wrong User Information";
	}

	?>
	<script>
location.replace("personal.php");
</script>
</body>
</html>
