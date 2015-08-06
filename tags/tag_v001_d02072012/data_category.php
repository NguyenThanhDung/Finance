<?php
//**************************************
// BUSINESS
//**************************************
function get_category($config, $id)
{
	return data_get_category($config, $id);
}

function get_category_list($config)
{
	return data_get_category_list($config);
}

function add_category($config, $name, $receipt, $description)
{
	// return value
	// 0 : fail
	// 1 : success
	return data_add_category($config, $name, $receipt, $description);
}

function edit_category($config, $id, $name, $receipt, $description)
{
	// return value
	// 0 : fail
	// 1 : success
	return data_edit_category($config, $id, $name, $receipt, $description);
}

function delete_category($config, $id)
{
	// return value
	// 0 : fail
	// 1 : success
	return data_remove_category($config, $id);
}


//**************************************
// DATA
//**************************************
function data_get_category($config, $id)
{
	// get connection
	$conn = get_connection($config);
	// create the SQL statement
	$sql = "SELECT * FROM category
			WHERE Id=$id";
	// execute the SQL statement
	$result = mysql_query($sql, $conn) or die(mysql_error());
	// fetch category info
	return mysql_fetch_array($result);
}

function data_get_category_list($config)
{
	// get connection
	$conn = get_connection($config);
	// create the SQL statement
	$sql = "SELECT * FROM category ORDER BY Name";
	// execute the SQL statement
	$result = mysql_query($sql, $conn) or die(mysql_error());
	// fetch data from $result to $category_list
	$count = 0;
	$category_list = 0;
	while($row = mysql_fetch_array($result))
	{
		if(!$category_list)
			$category_list = array();
		
		$category_list[$count]['Id'] = $row['Id'];
		$category_list[$count]['Name'] = $row['Name'];
		$category_list[$count]['Receipt'] = $row['Receipt'];
		$category_list[$count]['Description'] = $row['Description'];
			
		$count++;
	}
	
	return $category_list;
}

function data_add_category($config, $name, $receipt, $description)
{
	// get connection
	$conn = get_connection($config);
	// create the SQL statement
	$sql = "INSERT INTO category (Name,Receipt,Description) 
			VALUES ('$name',$receipt,'$description')";
	// execute the SQL statement
	return mysql_query($sql, $conn) or die(mysql_error());
}

function data_edit_category($config, $id, $name, $receipt, $description)
{
	// get connection
	$conn = get_connection($config);
	// create the SQL statement
	$sql = "UPDATE category 
			SET Name = '$name', Receipt = $receipt, Description = '$description'
			WHERE Id = $id";
	// execute the SQL statement
	return mysql_query($sql, $conn) or die(mysql_error());
}

function data_remove_category($config, $id)
{
	// get connection
	$conn = get_connection($config);
	// create the SQL statement
	$sql = "DELETE FROM category WHERE Id=$id";
	// execute the SQL statement
	return mysql_query($sql, $conn) or die(mysql_error());
}
?>