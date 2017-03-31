<?php

include ("account.php") ;

($dbh = mysql_connect($hostname, $username, $password)) or die ("Unable to connect to MySQL database.");

print "Connection to MySQL successful.<br><br>";

mysql_select_db($db);

$testReq = "SELECT * FROM recallcache WHERE year = '2000' AND make = 'saturn' AND model = 'ls'";

($query = mysql_query($testReq)) or die (mysql_error());

while ($result = mysql_fetch_array($query))
{
	$year = $result["year"];
	$make = $result["make"];
	$model = $result["model"];
	$recall = $result["recall"];
	print $year;
	print "<br><br>";
	print $make;
	print "<br><br>";
	print $model;
	print "<br><br>";
	print $recall;
}

?>
