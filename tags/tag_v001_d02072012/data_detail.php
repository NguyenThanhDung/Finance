<?php
//**************************************
// BUSINESS
//**************************************

function get_detail($config, $id)
{
	return data_get_detail($config, $id);
}

function get_detail_list($config, $filters)
{
	$categories = null;
	if(isset($filters['categories']))
		$categories = $filters['categories'];
	
	$detail = null;
	if(isset($filters['detail']))
		$detail = $filters['detail'];
	
	$from_date = null;
	if(isset($filters['from_date']))
		$from_date = $filters['from_date'];
	$to_date = null;
	if(isset($filters['to_date']))
		$to_date = $filters['to_date'];
	
	$from_amount = null;
	if(isset($filters['from_amount']))
		$from_amount = $filters['from_amount'];
	$to_amount = null;
	if(isset($filters['to_amount']))
		$to_amount = $filters['to_amount'];
	
	$description = null;
	if(isset($filters['description']))
		$description = $filters['description'];
	
	return data_filter_detail($config, $categories, $detail, $from_date, $to_date, $from_amount, $to_amount, $description);
}

function add_detail($config, $categoryId, $detail, $time, $amount, $description)
{
	return data_add_detail($config, $categoryId, $detail, $time, $amount, $description);
}

function edit_detail($config, $id, $categoryId, $detail, $time, $amount, $description)
{
	return data_edit_detail($config, $id, $categoryId, $detail, $time, $amount, $description);
}

function delete_detail($config, $id)
{
	return data_remove_detail($config, $id);
}

function get_available_money($config)
{
	$initial_money_str = get_setting($config, "InitialMoney");
	$initial_money = intval($initial_money_str["Value"]);
	
	$total_payment = data_get_total_payment($config);
	$total_receipt = data_get_total_receipt($config);
	
	return $initial_money - $total_payment + $total_receipt;
}


//**************************************
// DATA
//**************************************

function data_get_detail($config, $id)
{
	// get connection
	$conn = get_connection($config);
	// create the SQL statement
	$sql = "SELECT * FROM detail
			WHERE Id=$id";
	// execute the SQL statement
	$result = mysql_query($sql, $conn) or die(mysql_error());
	// fetch category info
	$detail = mysql_fetch_array($result);
	
	if($detail)
	{
		// get category information
		$category = data_get_category($config, $detail['CategoryId']);
		// set the name of category to detail
		$detail['Category'] = $category['Name'];
	}
	return $detail;
}

function data_get_detail_list($config)
{
	// get connection
	$conn = get_connection($config);
	// create the SQL statement
	$sql = "SELECT * FROM detail ORDER BY Time DESC";
	// execute the SQL statement
	$result = mysql_query($sql, $conn) or die(mysql_error());
	// fetch data from $result to $detail_list
	$count = 0;
	$detail_list = 0;
	while($row = mysql_fetch_array($result))
	{
		// init $detail_list
		if(!$detail_list)
			$detail_list = array();
			
		// fill standalone fields
		$detail_list[$count]['Id'] = $row['Id'];
		$detail_list[$count]['CategoryId'] = $row['CategoryId'];
		$detail_list[$count]['Detail'] = $row['Detail'];
		$detail_list[$count]['Time'] = $row['Time'];
		$detail_list[$count]['Amount'] = $row['Amount'];
		$detail_list[$count]['Description'] = $row['Description'];
			
		// fill category field
		$category = get_category($config, $row['CategoryId']);
		$detail_list[$count]['Category'] = $category['Name'];
		
		$count++;
	}
	
	return $detail_list;
}

function data_add_detail($config, $categoryId, $detail, $time, $amount, $description)
{
	// get connection
	$conn = get_connection($config);
	// create the SQL statement
	$sql = "INSERT INTO detail (CategoryId,Detail,Time,Amount,Description) 
			VALUES ('$categoryId','$detail',$time,$amount,'$description')";
	// execute the SQL statement
	return mysql_query($sql, $conn) or die(mysql_error());
}

function data_edit_detail($config, $id, $categoryId, $detail, $time, $amount, $description)
{
	// get connection
	$conn = get_connection($config);
	// create the SQL statement
	$sql = "UPDATE detail 
			SET CategoryId = '$categoryId', Detail = '$detail', Time = $time, Amount = $amount, Description = '$description'
			WHERE Id = $id";
	// execute the SQL statement
	return mysql_query($sql, $conn) or die(mysql_error());
}

function data_remove_detail($config, $id)
{
	// get connection
	$conn = get_connection($config);
	// create the SQL statement
	$sql = "DELETE FROM detail WHERE Id=$id";
	// execute the SQL statement
	return mysql_query($sql, $conn) or die(mysql_error());
}

function data_get_total_payment($config)
{
	// get connection
	$conn = get_connection($config);
	// create the SQL statement
	$sql = "SELECT SUM(Amount) AS TotalPayment FROM detail
			WHERE CategoryId IN
				(SELECT Id FROM category WHERE Receipt = 0)";
	// execute the SQL statement
	$result =  mysql_query($sql, $conn) or die(mysql_error());
	$first_row = mysql_fetch_array($result);
	return $first_row['TotalPayment'];
}

function data_get_total_receipt($config)
{
	// get connection
	$conn = get_connection($config);
	// create the SQL statement
	$sql = "SELECT SUM(Amount) AS TotalReceipt FROM detail
			WHERE CategoryId IN
				(SELECT Id FROM category WHERE Receipt = 1)";
	// execute the SQL statement
	$result =  mysql_query($sql, $conn) or die(mysql_error());
	$first_row = mysql_fetch_array($result);
	return $first_row['TotalReceipt'];
}

function data_filter_detail($config, $categories, $detail, $from_date, $to_date, $from_amount, $to_amount, $description)
{
	// get connection
	$conn = get_connection($config);
	
	// create the SQL statement
	$sql = "SELECT * FROM detail ";
	
	$where_clause = null;
	if($categories)
	{
		$cate_count = count($categories);
	
		$where_clause = "WHERE (CategoryId IN (";
		for($i = 0; $i < $cate_count; $i++)
		{
			$where_clause .= $categories[$i];
			if($i < $cate_count - 1)
				$where_clause .= ",";
		}
		$where_clause .= ")) ";
	}
	
	if($detail)
	{
		if($where_clause)
			$where_clause .= "AND ";
		else
			$where_clause .= "WHERE ";
			
		$where_clause .= "(Detail LIKE '%".$detail."%') ";
	}
	
	if($from_date)
	{		
		if($where_clause)
			$where_clause .= "AND ";
		else
			$where_clause .= "WHERE ";
		
		$where_clause .= "Time >= ".$from_date." ";
	}
	
	if($to_date)
	{		
		if($where_clause)
			$where_clause .= "AND ";
		else
			$where_clause .= "WHERE ";
		
		$where_clause .= "Time <= ".$to_date." ";
	}
	
	if($from_amount)
	{		
		if($where_clause)
			$where_clause .= "AND ";
		else
			$where_clause .= "WHERE ";
		
		$where_clause .= "Amount >= ".$from_amount." ";
	}
	
	if($to_amount)
	{		
		if($where_clause)
			$where_clause .= "AND ";
		else
			$where_clause .= "WHERE ";
		
		$where_clause .= "Amount <= ".$to_amount." ";
	}
	
	if($description)
	{
		if($where_clause)
			$where_clause .= "AND ";
		else
			$where_clause .= "WHERE ";
			
		$where_clause .= "(Description LIKE '%".$description."%') ";
	}
	
	$sql .= $where_clause." ";
	$sql .= "ORDER BY Time DESC";
	
	// execute the SQL statement
	$result = mysql_query($sql, $conn) or die(mysql_error());
	// fetch data from $result to $detail_list
	$count = 0;
	$detail_list = 0;
	while($row = mysql_fetch_array($result))
	{
		// init $detail_list
		if(!$detail_list)
			$detail_list = array();
			
		// fill standalone fields
		$detail_list[$count]['Id'] = $row['Id'];
		$detail_list[$count]['CategoryId'] = $row['CategoryId'];
		$detail_list[$count]['Detail'] = $row['Detail'];
		$detail_list[$count]['Time'] = $row['Time'];
		$detail_list[$count]['Amount'] = $row['Amount'];
		$detail_list[$count]['Description'] = $row['Description'];
			
		// fill category field
		$category = get_category($config, $row['CategoryId']);
		$detail_list[$count]['Category'] = $category['Name'];
		
		$count++;
	}
	
	return $detail_list;
}
?>