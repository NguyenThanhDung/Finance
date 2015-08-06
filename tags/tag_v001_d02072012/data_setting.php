<?php
//**************************************
// BUSINESS
//**************************************
function get_setting($config, $name)
{
	return data_get_setting($config, $name);
}

function edit_setting($config, $settings)
{
	if(data_edit_setting($config, "InitialMoney", $settings[0]) == 0)
		return 0;
	if(data_edit_setting($config, "LastCheckTime", $settings[1]) == 0)
		return 0;
	return 1;
}


//**************************************
// DATA
//**************************************
function data_get_setting($config, $name)
{
	// get connection
	$conn = get_connection($config);
	// create the SQL statement
	$sql = "SELECT Value FROM setting
			WHERE Name='$name'";
	// execute the SQL statement
	$result = mysql_query($sql, $conn) or die(mysql_error());
	// fetch category info
	return mysql_fetch_array($result);
}

function data_edit_setting($config, $name, $value)
{
	// get connection
	$conn = get_connection($config);
	// create the SQL statement
	$sql = "UPDATE setting 
			SET Value = '$value'
			WHERE name = '$name'";
	// execute the SQL statement
	return mysql_query($sql, $conn) or die(mysql_error());
}
?>