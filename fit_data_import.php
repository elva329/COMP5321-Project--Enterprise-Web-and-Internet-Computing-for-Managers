<?php


$dom = new DOMDocument();
$dom->load("data5.xml");
$userId="6";
// print_r(getArray($dom->documentElement));


$username = "root";
$servername = "localhost";
$password = "";
$dbName = "fitBar";


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
getArray($dom->documentElement,$mysql_connection,$userId);




function getArray($node,$mysql_connection,$userId) {
	
	
	$array = false;
	$startDate="";
	$endDate="";
	$value="";

	if ($node->hasAttributes()) {
		foreach ($node->attributes as $attr) {
			
			$array[$attr->nodeName] = $attr->nodeValue;
			if($attr->nodeName=="startDate"){
				$startDate=substr($attr->nodeValue, 0, 10);
				// echo $attr->nodeName .":".$startDate."###</br>";
			}else if($attr->nodeName=="endDate"){
				$endDate=substr($attr->nodeValue, 0, 10);
				// $endDate=$attr->nodeValue;
				// echo $attr->nodeName .":".$endDate."###</br>";
			}else if($attr->nodeName=="value"){
				$value=substr($attr->nodeValue, 0, 10);
				// $value=$attr->nodeValue;
				// echo "startDate:".$startDate."##  #";
				// echo "endDate:".$endDate."##  #";
				// echo "value:".$value."##  #";
				if($startDate!=""&& $endDate!=""&& $value!="" && $endDate-$startDate==1){
					$sql = " INSERT INTO sportData (userId,startTime,endTime,step)values (".$userId.",".$startDate.",".$endDate.",".$value.")";
					echo "</br>sql:".$sql;
					if(mysql_query($sql)){
						echo "***ok***</br>";
					}else{
						echo mysql_error();
					}
				}
				
				
			}
			
		}
	}

	if ($node->hasChildNodes()) {
		if ($node->childNodes->length == 1) {
			$array[$node->firstChild->nodeName] = getArray($node->firstChild,$mysql_connection,$userId);
		} else {
			foreach ($node->childNodes as $childNode) {
				if ($childNode->nodeType != XML_TEXT_NODE) {
					$array[$childNode->nodeName][] = getArray($childNode,$mysql_connection,$userId);
				}
			}
		}
	} else {
		return $node->nodeValue;
	}

	
	return $array;
}



mysql_close($mysql_connection);


?>
