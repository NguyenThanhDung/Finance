<?php
date_default_timezone_set("Asia/Saigon");

function get_connection($config)
{
	// open the connection
	$conn = mysql_connect($config['server_name'], $config['username'], $config['password']);
	if (!$conn)
	{
		die('Could not connect: ' . mysql_error());
	}
	// pick the database to use
	mysql_select_db($config['database_name'], $conn);	
	return $conn;
}
?>