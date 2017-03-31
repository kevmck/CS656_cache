<?php

include ("account.php") ;

($dbh = mysql_connect($hostname, $username, $password)) or die ("Unable to connect to MySQL database.");

mysql_select_db($db);

/*
$year = $_GET ["year"];
$year = mysql_real_escape_string ($year);
$make = $_GET ["make"];
$make = mysql_real_escape_string ($make);
$model = $_GET ["model"];
$model = mysql_real_escape_string ($model);
*/

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
	echo $recall;
}

else
{
	print "No results, fetching from API...<br><br>";
	//Everything below this is new and for testing purposes; erase if necessary!
	$apiData = file_get_contents('https://one.nhtsa.gov/webapi/api/Recalls/vehicle/modelyear/'.($year).'/make/'.($make).'/model/'.($model).'?format=json');
	echo $apiData;
	$cleanData = str_replace("'", "", $apiData);
	$insertCar = "INSERT INTO recallcache VALUES ('$year', '$make', '$model', '$cleanData')";
	($query = mysql_query($insertCar)) or die (mysql_error());
	print "<br><br>New car inserted into database.";
	return $cleanData;
}

?>
