<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Excel Importer</title>

<?php
require("../config.php");
require("../common.php");
require("../data_category.php");
require("../data_detail.php");

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
	$desc = $desc_string;
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
$cate_list = get_category_list($config);
echo "Category list:<br/>";
echo "<table border='1' cellspacing='0'>";
foreach($cate_list as $cate)
{
	echo "<tr>";
	echo "<td>".$cate['Id']."</td>";
	echo "<td>".$cate['Name']."</td>";
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
$added_row_count = $row_count;
?>

<form action="xls_importer.php" method="post">
	<input type="hidden" name="added_row_count" value="<?php echo $added_row_count; ?>"/>
	<input type="submit" value="Next" />
</form>

</body>
</html>