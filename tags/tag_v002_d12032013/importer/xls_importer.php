<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Excel Importer</title>

<?php
date_default_timezone_set("Asia/Saigon");

require("../config.php");
require("../utils.php");

function __autoload($class_name) {
    include "../classes/".$class_name . '.php';
}


function go_to_step($step, $extra_data)
{
	switch($step)
	{
	case 1:
		echo "<a href='xls_importer.php'>Try again</a><br/>";
		break;
	case 3:
?>
		<form action="xls_importer.php" method="post">
			<input type="hidden" name="step" value="3" />
			<input type="hidden" name="filename" value="<?php echo $extra_data; ?>"/>
			<input type="submit" value="Next" />
		</form>
<?php
		break;
	case 5:
?>
		<form action="xls_importer.php" method="post">
			<input type="hidden" name="step" value="5" />
			<input type="hidden" name="filename" value="<?php echo $extra_data[0]; ?>"/>
			<input type="hidden" name="import_type" value="<?php echo $extra_data[1]; ?>"/>
<?php
		if(isset($extra_data[2]) && isset($extra_data[3]) && isset($extra_data[4]))
		{
?>
			<input type="hidden" name="added_row_count" value="<?php echo $extra_data[2]; ?>"/>
			<input type="hidden" name="last_time" value="<?php echo $extra_data[3]; ?>"/>
			<input type="hidden" name="duplicated_time_count" value="<?php echo $extra_data[4]; ?>"/>
<?php
		}
?>
			<input type="submit" value="Next" />
		</form>
<?php
		break;
	}
}

function show_upload_file_form()
{
?>
	<form action="xls_importer.php" method="post" enctype="multipart/form-data">
		<input type="hidden" name="step" value="2" />
		<label for="file">Data file (.csv):</label>
		<input type="file" name="file" id="file" />
		<br />
		<input type="submit" name="submit" value="Submit" />
	</form>
<?php
}

function upload_file($file_info)
{
	if(!isset($file_info))
		header('Location: xls_importer.php');
	
	DLOG("file type = ".$file_info['type']);
	if($file_info['type'] != "text/csv" && $file_info['type'] != "application/vnd.ms-excel")
	{
		echo "Invalid file type.<br/>";
		echo "Please upload csv file only.<br/>";
		return 0;
	}

	if ($file_info["error"] > 0)
	{
		echo "Return Code: " . $file_info["error"] . "<br />";
		return 0;
	}
	else
	{
		echo "Upload: " . $file_info["name"] . "<br />";
		echo "Type: " . $file_info["type"] . "<br />";
		echo "Size: " . ($file_info["size"] / 1024) . " Kb<br />";
		echo "Temp file: " . $file_info["tmp_name"] . "<br />";

		$upload_time = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
		$stored_name = $upload_time."_".$file_info["name"];
		move_uploaded_file($file_info["tmp_name"],	$stored_name);
		echo "File is uploaded to importer/".$stored_name."<br/>";
		
		return $stored_name;
	}
}

function ask_kind_of_importing()
{
	if(!isset($_POST['filename']))
		header('Location: xls_importer.php');
	
	echo "Which kind of importing do you want to perform?<br/>";
?>
	<form action="xls_importer.php" method="post">
		<input type="hidden" name="step" value="4" />
		<input type="hidden" name="filename" value="<?php echo $_POST['filename']; ?>"/>
		<input type="submit" name="submit" value="Clear existing data and import" />
		<input type="submit" name="submit" value="Merge with existing data" />
	</form>
<?php
}

function import_category($filename)
{
	if(!isset($filename))
	{
		header('Location: xls_importer.php');
	}
	
	$file = fopen($filename, "r") or exit("Unable to open file ".$filename);
	
	$categories = array();
	$count = 0;
	while(!feof($file))
	{
		$line = fgets($file);		
		$cells = parse_line_to_array($line);		
		if($cells)
		{
			$i = 0;
			while($i < $count)
			{
				if($cells[1] == $categories[$i])
					break;
				$i++;
			}
			if($i == $count)
			{
				$categories[$count] = $cells[1];
				$count++;
			}
		}
	}	
	fclose($file);
	
	echo "count = " . $count . "<br/>";
	for($i = 0; $i < $count; $i++)
	{
		$category = new CCategory(0, $categories[$i], 0, null);
		CCategoryManager::AddCategory($category);
		echo ($i+1) . ". " . $categories[$i] . "<br/>";
	}
}

function import_record($filename, $added_row_count, $last_time, $duplicated_time_count)
{
	if(!isset($filename))
	{
		header('Location: xls_importer.php');
	}
	
	$file = fopen($filename, "r") or exit("Unable to open file ".$filename);
	
	echo "<table border='1' cellspacing='0'>";
	$count = 0;
	while(!feof($file))
	{
		$line = fgets($file);				
		$count++;
		
		if($count <= $added_row_count)
			continue;
		
		if(($count - $added_row_count) > 200)
		{
			$count--;
			break;
		}
		
		echo "<tr>";
		$cells = parse_line_to_array($line);		
		if($cells)
		{
			$category = CCategoryManager::GetCategoryByName($cells[1]);
			$category_id = $category->GetId();
			$detail = $cells[3];
			$time = $cells[0];
			if($time == $last_time)
			{
				$duplicated_time_count++;
				$time += $duplicated_time_count * 60 * 60;
			}
			else
			{
				$last_time = $time;
				$duplicated_time_count = 0;
			}
			$amount = $cells[2];
		
			$record = new CRecord(0, $category_id, $detail, $time, $amount, null);
			CRecordManager::AddRecord($record);
			
			echo "<td>$category_id</td>";
			echo "<td>".$category->GetName()."</td>";
			echo "<td>$detail&nbsp;</td>";
			echo "<td>".date("d-m-Y H:i:s", $time)."</td>";
			echo "<td>$amount</td>";
		}
		echo "</tr>";
	}
	fclose($file);
	echo "</table>";
	
	$result = array();
	$result[0] = $count;
	$result[1] = $last_time;
	$result[2] = $duplicated_time_count;
	return $result;
}

function parse_line_to_array($line)
{
	if(!is_numeric($line[0]))
		return null;
		
	DLOG("line1=$line");
	$line = convert_seperator($line);
	DLOG("line2=$line");
	$cells = explode("|", $line);
	
	$cells[0] = parse_time($cells[0]);
	$cells[2] = parse_amount($cells[2]);
	$cells[3] = parse_description($cells[3]);
	
	return $cells;
}

function convert_seperator($string)
{
	$is_in_quote = false;
	$len = strlen($string);
	
	for($i = 0; $i < $len; $i++)
	{
		if($string[$i] == '"')
			$is_in_quote = !$is_in_quote;
			
		if($string[$i] == ',' && $is_in_quote == false)
			$string[$i] = '|';
	}
	
	return $string;
}

function parse_time($date_string)
{
	$dates = explode("-", $date_string);
	
	$dates[2] = intval($dates[2]);
	$dates[1] = intval($dates[1]);
	$dates[0] = intval($dates[0]);	
	
	return mktime(0, 0, 0, $dates[1], $dates[2], $dates[0]);
}

function parse_amount($amount_string)
{
	$amount = $amount_string;
	if(is_numeric($amount[0]) == false)
	{
		if($amount[0] == '"' && $amount[1] == '-')
		{
			$index = 2;
			$len = strlen($amount) - 3;
		}
		else if($amount[0] == '"')
		{
			$index = 1;
			$len = strlen($amount) - 2;
		}
		else if($amount[0] == '-')
		{
			$index = 1;
			$len = strlen($amount) - 1;
		}
		
		$amount = substr($amount, $index, $len);
		$amount = implode(explode(",", $amount));
	}
	return intval($amount);
}

function parse_description($desc_string)
{
	DLOG("desc_string='$desc_string'");
	$desc = $desc_string;
	
	if(isset($desc)==false || strlen($desc)==0)
	{
		return "";
	}
	
	if($desc[0] == '"')
	{
		$len = strlen($desc);
		$desc = substr($desc, 1, $len - 2);
	}
	return $desc;
}

function add_data($conn, $config, $categoryId, $detail, $time, $amount, $description)
{
	// create the SQL statement
	$sql = "INSERT INTO detail (CategoryId,Detail,Time,Amount,Description) 
			VALUES ('$categoryId','$detail',$time,$amount,'$description')";
	// execute the SQL statement
	return mysql_query($sql, $conn) or die(mysql_error());
}
?>

</head>
<body>

<?php
/*$categories = CCategoryManager::GetAllCategories();
echo "Category list:<br/>";
echo "<table border='1' cellspacing='0'>";
foreach($categories as $category)
{
	echo "<tr>";
	echo "<td>".$category->GetId()."</td>";
	echo "<td>".$category->GetName()."</td>";
	echo "</tr>";
}
echo "</table>";

$conn = get_connection($config);

$filename = "Finance.csv";
$file = fopen($filename, "r") or exit("Unable to open file ".$filename);

$last_time = 0;
$duplicated_count = 0;
$added_row_count = isset($_POST["added_row_count"]) ? $_POST["added_row_count"] : 0;
$row_count = 0;

echo "Added rows:<br/>";
echo "<table border='1' cellspacing='0'>";
while(!feof($file))
{		
	$line = fgets($file);	
	
	$row_count++;
	if($row_count <= $added_row_count)
		continue;
	if(($row_count - $added_row_count) > 200)
	{
		$row_count--;
		break;
	}
	
	$row_string = "<tr>";
	if(is_numeric($line[0]))
	{
		$line = convert_seperator($line);
		$cells = explode("|", $line);
		
		// Date & Time
		$time = parse_time($cells[0]);
		if($time == $last_time)
		{
			$duplicated_count++;
			$time += $duplicated_count * 60 * 60;
		}
		else
		{
			$last_time = $time;
			$duplicated_count = 0;
		}
		$row_string .= "<td>".date('d-m-Y H:i:s', $time)."</td>";
		
		// Category
		$category = $cells[1];		
		$row_string .= "<td>".$category."</td>";
		$category_id = "&nbsp;";
		foreach($cate_list as $cate)
		{
			if($cate['Name'] == $category)
			{
				$category_id = $cate['Id'];
				break;
			}
		}
		$row_string .= "<td>".$category_id."</td>";
		
		// Amount
		$amount = parse_amount($cells[2]);		
		$row_string .= "<td>".$amount."</td>";
		
		// Description
		$description = parse_description($cells[3]);
		$row_string .= "<td>".$description."&nbsp;</td>";
		
		// Insert into database
		add_data($conn, $config, $category_id, $description, $time, $amount, "");
	}
	$row_string .= "</tr>";	
	echo $row_string;
}
echo "</table>";
fclose($file);

echo "Added rows from ".($added_row_count+1)." to ".$row_count."<br/>";
$added_row_count = $row_count;*/
?>

<!--<form action="xls_importer.php" method="post">
	<input type="hidden" name="added_row_count" value="<?php echo $added_row_count; ?>"/>
	<input type="submit" value="Next" />
</form>-->

<?php
$step = isset($_POST['step']) ? $_POST['step'] : 1;
switch($step)
{
case 1:
	show_upload_file_form();
	break;
	
case 2:
	$filename = upload_file($_FILES["file"]);
	if($filename)
	{
		go_to_step(3, $filename);
	}
	else
	{
		go_to_step(1, null);
	}
	break;
	
case 3:
	ask_kind_of_importing();
	break;
	
case 4:
	if($_POST['submit'] == "Clear existing data and import")
	{
		if(!CCategoryManager::ClearAllCategories())
			break;
		echo "Cleared all categories.<br/>";
		$import_type = "override";
	}
	else if($_POST['submit'] == "Merge with existing data")
	{
		$import_type = "merge";
	}
	else
	{
		header('Location: xls_importer.php');
	}
	
	$filename = $_POST['filename']; 
	import_category($filename);
	
	$extra_data = array();
	$extra_data[0] = $filename;		// name of uploaded file
	$extra_data[1] = $import_type;	// type of importing
	go_to_step(5, $extra_data);
	break;
	
case 5:
	$filename = $_POST['filename'];
	$import_type = $_POST['import_type'];
	$added_row_count = isset($_POST['added_row_count']) ? $_POST['added_row_count'] : 0;
	$last_time = isset($_POST['last_time']) ? $_POST['last_time'] : 0;
	$duplicated_time_count = isset($_POST['duplicated_time_count']) ? $_POST['duplicated_time_count'] : 0;
	
	if(isset($filename) && isset($import_type))
	{
		if($import_type == "override" && $added_row_count == 0)
		{
			if(!CRecordManager::ClearAllRecords())
				break;
			echo "Cleared all records.<br/>";
		}
	}
	else
	{
		header('Location: xls_importer.php');
	}
	
	$result = import_record($filename, $added_row_count, $last_time, $duplicated_time_count);
	echo "Added row from $added_row_count to ".$result[0]."<br/>";
	
	$extra_data = array();
	$extra_data[0] = $filename;		// name of uploaded file
	$extra_data[1] = $import_type;	// type of importing
	$extra_data[2] = $result[0];	// number of added rows
	$extra_data[3] = $result[1];	// time of recent added row
	$extra_data[4] = $result[2];	// number of duplicated
	go_to_step(5, $extra_data);
	break;
	
default:
	show_upload_file_form();
}
?>

</body>
</html>