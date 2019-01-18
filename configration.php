<?php
date_default_timezone_set('UTC');

	/*$con = mysql_connect('localhost','aplussoz_plantap','plantap@123') or die(mysql_error());	
	$db = mysql_select_db('aplussoz_plantap',$con) or die(mysql_error());*/


	$con = mysqli_connect("localhost","aplussoz_plantap","plantap@123","aplussoz_plantap");

	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

?>