<?php
session_start();
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Finance - Detail</title>
</head>
<body>

<p><a href="category.php">Manage Category</a> | <a href="setting.php">Setting</a></p>

<h3>Detail</h3>

<?php
date_default_timezone_set("Asia/Saigon");

require("config.php");
require("utils.php");

function __autoload($class_name) {
	include "classes/".$class_name . '.php';
}

//*********************
// FUNCTIONS
//*********************
function show_available_money()
{
	$available_money = CStatistic::GetInstance()->GetAvailableMoney();
	echo "<p>Available: <b>$available_money</b></p>";
}

function list_detail()
{
	$category_ids 	= isset($_SESSION['filters']['categories']) ? $_SESSION['filters']['categories'] 	: null;
	$detail 		= isset($_SESSION['filters']['detail']) 	? $_SESSION['filters']['detail'] 		: null;
	$from_date 		= isset($_SESSION['filters']['from_date']) 	? $_SESSION['filters']['from_date']		: null;
	$to_date 		= isset($_SESSION['filters']['to_date']) 	? $_SESSION['filters']['to_date'] 		: null;
	$from_amount	= isset($_SESSION['filters']['from_amount'])? $_SESSION['filters']['from_amount'] 	: null;
	$to_amount 		= isset($_SESSION['filters']['to_amount']) 	? $_SESSION['filters']['to_amount'] 	: null;
	$description	= isset($_SESSION['filters']['description'])? $_SESSION['filters']['description']	: null;
	
	$filter = new CFilter(0, $category_ids, $detail, $from_date, $to_date, $from_amount, $to_amount, $description);
	CRecordManager::GetInstance()->SetFilter($filter);
	
	$records = CRecordManager::GetInstance()->GetRecords();
	if($records)
	{
		$num = CRecordManager::GetInstance()->GetNumberOfRecord();
		if($num < 2)
			echo "<p>There is $num record</p>";
		else
			echo "<p>There are $num records</p>";
			
		$filter = CRecordManager::GetInstance()->GetFilter();
		if($filter->IsFiltering())
		{
			$total_money = CStatistic::GetInstance()->GetTotalMoney($filter);
			echo "<p>Total: $total_money</p>";
		}
		
		echo "<table border='1' cellspacing='0'>";
		echo "<tr>";
		echo "	<th>ID</th>";
		echo "	<th>Category</th>";
		echo "	<th>Detail</th>";
		echo "	<th>Time</th>";
		echo "	<th>Amount</th>";
		echo "	<th>Description</th>";
		echo "	<th colspan='2'>Action</th>";
		echo "</tr>";
		
		foreach($records as $record)
		{
			echo "<tr>";
			echo "	<td>".$record->GetId()."</td>";
			echo "	<td>".$record->GetCategory()->GetName()."</td>";
			echo "	<td>".$record->GetDetail()."&nbsp;</td>";
			echo "	<td>".date("Y-m-d H:i",$record->GetTime())."</td>";
			echo "	<td>".$record->GetAmount()."</td>";
			echo "	<td>".$record->GetDescription()."&nbsp;</td>";
			echo "	<td><a href='detail.php?action_type=edit&id=".$record->GetId()."'>Edit</a></td>";
			echo "	<td><a href='detail.php?action_type=delete&id=".$record->GetId()."'>Delete</a></td>";
			echo "<tr/>";
		}
		
		echo "</table>";
	}
	else
	{
		echo "There is no any record";
	}
}

function submit_add_detail()
{
	$category_id = $_POST['categoryId'];
	$detail = $_POST['detail'];	
	$time = mktime($_POST['detail_form_time_hour'], 
					$_POST['detail_form_time_minute'], 
					$_POST['detail_form_time_second'], 
					$_POST['detail_form_time_month'], 
					$_POST['detail_form_time_day'], 
					$_POST['detail_form_time_year']);	
	$amount = $_POST['amount'];
	$description = $_POST['description'];
	
	$record = new CRecord(0, $category_id, $detail, $time, $amount, $description);
	return CRecordManager::GetInstance()->AddRecord($record);
}

function submit_edit_detail()
{
	$id = $_POST['id'];
	$category_id = $_POST['categoryId'];
	$detail = $_POST['detail'];	
	$time = mktime($_POST['detail_form_time_hour'], 
					$_POST['detail_form_time_minute'], 
					$_POST['detail_form_time_second'], 
					$_POST['detail_form_time_month'], 
					$_POST['detail_form_time_day'], 
					$_POST['detail_form_time_year']);	
	$amount = $_POST['amount'];
	$description = $_POST['description'];
	
	$record = new CRecord($id, $category_id, $detail, $time, $amount, $description);
	return CRecordManager::GetInstance()->UpdateRecord($record);
}

function submit_delete_detail()
{
	return CRecordManager::GetInstance()->DeleteRecord($_POST['id']);
}

function confirmDeleteDetail()
{
	$record = CRecordManager::GetInstance()->GetRecordById($_GET['id']);
	
	echo "<p>Are you sure want to delete below record?</p>";
	echo "<p>Category: ".$record->GetCategory()->GetName()."<br/>";
	echo "Detail: ".$record->GetDetail()."<br/>";
	echo "Time: ".date("Y-m-d H:i",$record->GetTime())."<br/>";
	echo "Amount: ".$record->GetAmount()."<br/>";
	echo "Description: ".$record->GetDescription()."</p>";
	
?>
<form action="detail.php" method="post">
	<input type="hidden" name="action_type" value="submit_delete"/>
	<input type="hidden" name="id" value="<?php echo $record->GetId(); ?>"/>
	
	<table border="0">
		<tr>
			<td><a href="detail.php?action_type=list">No</a></td>
			<td><input type="submit" value="Yes" /></td>
		</tr>
	</table>
</form>
<?php
}

function show_add_detail_form()
{
	show_detail_form("add", 0);
}

function show_edit_detail_form($id)
{
	$record = CRecordManager::GetInstance()->GetRecordById($id);
	if($record)
		show_detail_form("edit", $record);
	else
		echo "<p>Error: Can't get detail that has id = $id</p>";
}

function showCategoryListBox($selected_category_id)
{
	$categories = CCategoryManager::GetInstance()->GetAllCategories();
	
	echo "<select name='categoryId'>";
	foreach($categories as $category)
	{
		$tag = "<option value='".$category->GetId()."'";
		
		if($category->GetId() == $selected_category_id)
			$tag .= " selected='selected'";
		
		$tag .= ">".$category->GetName()."</option>";
		echo $tag;
	}
	echo "</select>";
}

function show_detail_form($form_type, $record)
{	
?>

<?php if($form_type == "add") {?>
<h4>Add new record</h4>
<?php } else if($form_type == "edit") {?>
<h4>Edit record</h4>
<?php } else {} ?>

<form action="detail.php" method="post">

<!-- Request type -->
<?php if($form_type == "add") {?>
<input type="hidden" name="action_type" value="submit_add"/>
<?php } else if($form_type == "edit") {?>
<input type="hidden" name="action_type" value="submit_edit"/>
<input type="hidden" name="id" value="<?php echo $record->GetId(); ?>"/>
<?php } else {} ?>

<table border="0">
<tr>
	<td>Category:</td>
	<td>
<?php
		$selected_category_id = -1;
		if($form_type == "edit")
			$selected_category_id = $record->GetCategoryId();
		showCategoryListBox($selected_category_id);
?>
	</td>
</tr>
<tr>
	<td>Detail:</td>
	<td>
		<input type="text" name="detail"
			value="<?php if($form_type == 'edit') echo $record->GetDetail();?>"
		/>
	</td>
</tr>
<tr>
	<td>Time:</td>
	<td>
<?php
		if($form_type == 'edit')		
			showTimeBox("detail_form_time", $record->GetTime());
		else
			showTimeBox("detail_form_time", 0);
?>
	</td>
</tr>
<tr>
	<td>Amount:</td>
	<td>
		<input type="text" name="amount"
			value="<?php if($form_type == 'edit') echo $record->GetAmount();?>"
		/>
	</td>
</tr>
<tr>
	<td>Description:</td>
	<td>
		<textarea name="description" rows="5" cols="30"><?php if($form_type == "edit") echo $record->GetDescription();?></textarea>
	</td>
</tr>
<tr>
	<td></td>
	
<!-- Submit button -->
<?php if($form_type == "add") {?>
	<td><input type="submit" value="Add" /></td>
<?php } else if($form_type == "edit") {?>
	<td><input type="submit" value="Update" /></td>
<?php } else {} ?>

</tr>
</table>
</form>
<?php
}

function show_filter_form($config)
{
	$filters = $_SESSION['filters'];
?>
<h4>Filter</h4>
<form action="detail.php" method="post">
<input type="hidden" name="action_type" value="filter"/>
<table width="600" border="0" cellspacing="0">
	<!-- CATEGORY -->
	<tr>		
		<td>
			<input type="checkbox" name="enable_filter[]" value="category"
				<?php 
					if(isset($filters['categories']))
						echo "checked='checked'";
				?>
			>Category</input>
		</td>
		<td>
			<?php
			$categories = CCategoryManager::GetInstance()->GetAllCategories();
			foreach($categories as $category)
			{
				echo "<input type='checkbox' name='category_filter[]' ";
				echo "value='".$category->GetId()."' ";
				
				if(isset($filters['categories']))
				{
					foreach($filters['categories'] as $cate_filter)
						if($category->GetId() == $cate_filter)
						{
							echo "checked='checked'";
							break;
						}
				}
								
				echo "/>".$category->GetName()." ";
			}
			?>
		</td>
	</tr>
	
	<!-- DETAIL -->
	<tr>
		<td width="130">
			<input type="checkbox" name="enable_filter[]" value="detail"
				<?php 
					if(isset($filters['detail']))
						echo "checked='checked'";
				?>
			>Detail</input>
		</td>
		<td>
			<input type="text" name="detail_text"
				<?php
					if(isset($filters['detail']))
						echo "value='".$filters['detail']."'";
				?>
			/>
		</td>
	</tr>
	
	<!-- FROM DATE -->
	<tr>
		<td>
			<input type="checkbox" name="enable_filter[]" value="from_date"
				<?php 
					if(isset($filters['from_date']))
						echo "checked='checked'";
				?>
			>From date</input>
		</td>
		<td>
			<?php 
				if(isset($filters['from_date']))
					showTimeBox("filter_detail_from_date", $filters['from_date']);
				else
					showTimeBox("filter_detail_from_date", 0);
			?>
		</td>
	</tr>	
	
	<!-- TO DATE -->
	<tr>
		<td>
			<input type="checkbox" name="enable_filter[]" value="to_date"
				<?php 
					if(isset($filters['to_date']))
						echo "checked='checked'";
				?>
			>To date</input>
		</td>
		<td>
			<?php 
				if(isset($filters['to_date']))
					showTimeBox("filter_detail_to_date", $filters['to_date']);
				else
					showTimeBox("filter_detail_to_date", 0);
			?>
		</td>
	</tr>
	
	<!-- FROM AMOUNT -->
	<tr>
		<td>
			<input type="checkbox" name="enable_filter[]" value="from_amount"
				<?php 
					if(isset($filters['from_amount']))
						echo "checked='checked'";
				?>
			>From amount</input>
		</td>
		<td>
			<input type="text" name="amount_from_filter"
				<?php
					if(isset($filters['from_amount']))
						echo "value='".$filters['from_amount']."'";
				?>
			/>
		</td>
	</tr>
	
	<!-- TO AMOUNT -->
	<tr>
		<td>
			<input type="checkbox" name="enable_filter[]" value="to_amount"
				<?php 
					if(isset($filters['to_amount']))
						echo "checked='checked'";
				?>
			>To amount</input>
		</td>
		<td>
			<input type="text" name="amount_to_filter"
				<?php
					if(isset($filters['to_amount']))
						echo "value='".$filters['to_amount']."'";
				?>
			/>
		</td>
	</tr>
	
	<!-- DESCRIPTION -->
	<tr>
		<td>
			<input type="checkbox" name="enable_filter[]" value="description"
				<?php 
					if(isset($filters['description']))
						echo "checked='checked'";
				?>
			>Description</input>
		</td>
		<td>
			<input type="text" name="desc_text"
				<?php
					if(isset($filters['description']))
						echo "value='".$filters['description']."'";
				?>
			/>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type="submit" value="Filter" />
		</td>
	</tr>
</table>
</form>
<?php
}

function apply_filter($enable_filter)
{
	$filters = array();
	
	$count = count($enable_filter);
	for($i=0; $i<$count; $i++)
	{
		$name = $enable_filter[$i];
		switch($name)
		{
		case "category":
			$filters['categories'] = $_POST['category_filter'];
			break;
		case "detail":
			$filters['detail'] = $_POST['detail_text'];
			break;
		case "from_date":
			$filters['from_date'] = mktime($_POST['filter_detail_from_date_hour'], 
											$_POST['filter_detail_from_date_minute'], 
											$_POST['filter_detail_from_date_second'], 
											$_POST['filter_detail_from_date_month'], 
											$_POST['filter_detail_from_date_day'], 
											$_POST['filter_detail_from_date_year']);
			break;
		case "to_date":
			$filters['to_date'] = mktime($_POST['filter_detail_to_date_hour'], 
											$_POST['filter_detail_to_date_minute'], 
											$_POST['filter_detail_to_date_second'], 
											$_POST['filter_detail_to_date_month'], 
											$_POST['filter_detail_to_date_day'], 
											$_POST['filter_detail_to_date_year']);
			break;
		case "from_amount":
			$filters['from_amount'] = $_POST['amount_from_filter'];
			break;
		case "to_amount":
			$filters['to_amount'] = $_POST['amount_to_filter'];
			break;
		case "description":
			$filters['description'] = $_POST['desc_text'];
			break;
		}
	}
	$_SESSION['filters'] = $filters;
}

//*********************
// MAIN PROCESS
//*********************
$action_type = "list";
if(isset($_REQUEST['action_type']))
	$action_type = $_REQUEST['action_type'];
	
switch($action_type)
{
case "filter":
	apply_filter($_POST['enable_filter']);
case "list":
	show_available_money();
?>	
<table border="0" cellpadding="10">
	<tr>
		<td width="400"><?php show_add_detail_form(); ?></td>
		<td><?php show_filter_form($config); ?></td>
	</tr>
</table>
<?php
	list_detail();
	break;
	
case "submit_add":
	$isSuccess = submit_add_detail();
	if($isSuccess)
		echo "<p>1 detail is added</p>";
	else
		echo "<p>CAN NOT add detail</p>";
		
	show_available_money();
?>	
<table border="0" cellpadding="10">
	<tr>
		<td width="400"><?php show_add_detail_form(); ?></td>
		<td><?php show_filter_form($config); ?></td>
	</tr>
</table>
<?php
	list_detail();
	break;
	
case "edit":
	show_edit_detail_form($_GET['id']);
	break;
	
case "submit_edit":
	$isSuccess = submit_edit_detail();
	if($isSuccess)
		echo "<p>1 detail is updated</p>";
	else
		echo "<p>CAN NOT update detail</p>";
		
	show_available_money();
?>	
<table border="0" cellpadding="10">
	<tr>
		<td width="400"><?php show_add_detail_form(); ?></td>
		<td><?php show_filter_form($config); ?></td>
	</tr>
</table>
<?php
	list_detail();
	break;
	
case "delete":
	confirmDeleteDetail();
	break;
	
case "submit_delete":
	$isSuccess = submit_delete_detail();
	if($isSuccess)
		echo "<p>1 detail is deleted</p>";
	else
		echo "<p>CAN NOT delete detail</p>";
		
	show_available_money();
?>	
<table border="0" cellpadding="10">
	<tr>
		<td width="400"><?php show_add_detail_form(); ?></td>
		<td><?php show_filter_form($config); ?></td>
	</tr>
</table>
<?php
	list_detail();
	break;
}

?>

</body>
</html>