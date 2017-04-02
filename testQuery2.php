<?php

include ("account.php") ;

($dbh = mysql_connect($hostname, $username, $password)) or die ("Unable to connect to MySQL database.");

mysql_select_db($db);

$year = $_REQUEST ["year"];
$year = mysql_real_escape_string ($year);
$make = $_REQUEST ["make"];
$make = mysql_real_escape_string ($make);
$model = $_REQUEST ["model"];
$model = mysql_real_escape_string ($model);

$testReq2 = "SELECT * FROM recallcache WHERE year = '$year' AND make = '$make' AND model = '$model'";

($query = mysql_query($testReq2)) or die (mysql_error());

if ($result = mysql_fetch_array($query))
{
	$recall = $result["recall"];
	$addCount = $result["count"];
	$addCount++;
	$incrementCount = "UPDATE recallcache SET count = '$addCount' WHERE year = '$year' AND make = '$make' AND model = '$model'";
	($query = mysql_query($incrementCount)) or die (mysql_error());
	echo $recall;
}

else
{
	$apiData = file_get_contents('https://one.nhtsa.gov/webapi/api/Recalls/vehicle/modelyear/'.($year).'/make/'.($make).'/model/'.($model).'?format=json');
	$cleanData = str_replace("'", "", $apiData);
	$count = '1';
	$insertCar = "INSERT INTO recallcache VALUES ('$year', '$make', '$model', '$cleanData', '$count')";
	($query = mysql_query($insertCar)) or die (mysql_error());
	echo $cleanData;
}

?>
