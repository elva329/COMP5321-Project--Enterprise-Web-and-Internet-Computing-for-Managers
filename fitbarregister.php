<html>
<?php
//注册成功之后自动为登陆状态
session_start();

$userName ="";
$password = "";
$email = "";
$birthday = "";
$phoneNumber = "";
// $country = "";
$city = "";
$area = "";
$id = "";
$id_option = "";
$photoProfilePath="../uploads/";
$sportDataPath="../uploads/";

		//this is for uploaded file
//move_uploaded_file($_FILES['photoProfile'] ['tmp_name'],"../uploads/".$_FILES['photoProfile']['name']);
//move_uploaded_file($_FILES['sportData']['tmp_name'], "../uploads/".$_FILES['sportData']['name']);

if ($_SERVER['REQUEST_METHOD']=='POST') {
 	# code...
	$userName = $_POST['userName'];
	$password = $_POST['password'];
	$email = $_POST['email'];
	$birthday = $_POST['birthday'];
	$phoneNumber = $_POST['phoneNumber'];
	$area = $_POST['area'];
 	// $country = $POST['country'];
 	// $city = $POST['city'];
	if(isset($_POST['id_option'])){
		$id_option = $_POST['id_option'];
	}
	if(isset($_POST['id'])){
		$id = $_POST['id'];
	}
	if(isset($_POST['photoProfile'])){
		$photoProfile = $_POST['photoProfile'];
		$photoProfilePath="../uploads/".$photoProfile;

		// move_uploaded_file($_FILES['photoProfile'] ['tmp_name'],"../uploads/".$_FILES['photoProfile']['name']);
	}
	if(isset($_POST['sportData'])){
		$sportData = $_POST['sportData'];
		$sportDataPath="../uploads/".$sportData;
		// move_uploaded_file($_FILES['sportData']['tmp_name'], "../uploads/".$_FILES['sportData']['name']);
	}
	
 	// $id = $POST['id'];//wechat id or apple id
 	// $id_option = $POST['id_option'];// wechat or apple
 	// $photoProfile = $POST['photoProfile'];
 	// $sportData = $POST['sportData'];
 		//this is for uploaded file

}

// $photoProfilePath="../uploads/".$_FILES['photoProfile']['name'];
// $sportDataPath="../uploads/".$_FILES['sportData']['name'];

$dbusername = "root";
$dbservername = "localhost";
$dbpassword = "";
$dbName = "fitBar";

	//start to connect the database
if($mysql_connection = mysql_connect($dbservername,$dbusername,$dbpassword)){
	// echo "connection successfully!<br>";
}
	//select FirBar DB
mysql_select_db($dbName);
	//sql standard insert command 
	//for different id value
if ($id_option == "wechatid") {
		# code...
	$sql = "INSERT INTO userInfo (userName,password,email,birthday,phoneNumber,photoProfile,sportData,area,appleId,wechatId) 
	VALUES ( '".$userName."','".$password."','".$email."','".$birthday."','".$phoneNumber."','".$photoProfilePath."','".$sportDataPath."','".$area."','','".$id."')";
}else{
		// $sql = "INSERT INTO userInfo (userName,password,email,birthday,phoneNumber,area,appleId,wechatId,'../uploads/','../uploads/') 
			// VALUES ( $userName,$password,$email,$birthday,$phoneNumber,$area,$id,'')";
	$sql = "INSERT INTO userInfo (userName,password,email,birthday,phoneNumber,photoProfile,sportData,area,appleId,wechatId) 
	VALUES ( '".$userName."','".$password."','".$email."','".$birthday."','".$phoneNumber."','".$photoProfilePath."','".$sportDataPath."','".$area."','".$id."','')";
}

	//execute
if(mysql_query($sql)){
	// echo "the data has been inserted successfully.<br>";
	//插入用户成功才会执行插入关系

	$sql = "SELECT * FROM userInfo WHERE email = '".$email."'";//这里由username改为了email，因为在数据库中只有email和id是唯一的
	$result = mysql_query($sql);
	//get the id of the new register user
	if($result){
		while ($row = mysql_fetch_assoc($result)) {
	# code...
			$userId = $row['id'];
			$_SESSION['userName'] = $row['userName'];
			$_SESSION['userId'] = $row['id'];
			// echo "userName:".$_SESSION['userName']."</br>";
			// echo "userId:".$_SESSION['userId']."</br>";
			// echo "<a href = 'personal.php'><b>Go To Personal Page</b></a>";
			if($userId!="1"&&$userId!="3"){
				//insert new relation in the the relation table
				$sql = "INSERT INTO userRelation (user1Id, user2Id) VALUES ('".$userId."','1')";

				if(mysql_query($sql)){
					// echo "insert successfully.<br>";
				}else{
					echo "insert wrong.<br>";
					echo mysql_error();
				}

				$sql = "INSERT INTO userRelation (user1Id, user2Id) VALUES ('".$userId."','3')";

				if(mysql_query($sql)){
					// echo "insert successfully.<br>";
					
				}else{
					echo "insert wrong.<br>";
					echo mysql_error();
				}
			}
		}
	}else{
		echo "select wrong.<br>";
		echo mysql_error();
	}


}else{
	echo mysql_error();
}
//get the id of the new register user

mysql_close($mysql_connection);

?>
<script>
location.replace("personal.php");
</script>
</html>
