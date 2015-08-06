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
require("config.php");
require("common.php");
require("data_setting.php");
require("data_category.php");
require("data_detail.php");
require("utils.php");

//*********************
// FUNCTIONS
//*********************
function show_available_money($config)
{
	$available_money = get_available_money($config);
	echo "<p>Available: <b>$available_money</b></p>";
}

function list_detail($config)
{
	$detail_list = get_detail_list($config, $_SESSION['filters']);
	if($detail_list)
	{
		$num = count($detail_list);
		if($num < 2)
			echo "<p>There is $num record</p>";
		else
			echo "<p>There are $num records</p>";
		
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
		
		for($i = 0; $i < $num; $i++)
		{
			echo "<tr>";
			echo "	<td>".$detail_list[$i]['Id']."</td>";
			echo "	<td>".$detail_list[$i]['Category']."</td>";
			echo "	<td>".$detail_list[$i]['Detail']."&nbsp;</td>";
			echo "	<td>".date("Y-m-d H:i",$detail_list[$i]['Time'])."</td>";
			echo "	<td>".$detail_list[$i]['Amount']."</td>";
			echo "	<td>".$detail_list[$i]['Description']."&nbsp;</td>";
			echo "	<td><a href='detail.php?action_type=edit&id=".$detail_list[$i]['Id']."'>Edit</a></td>";
			echo "	<td><a href='detail.php?action_type=delete&id=".$detail_list[$i]['Id']."'>Delete</a></td>";
			echo "<tr/>";
		}
		
		echo "</table>";
	}
	else
	{
		echo "There is no any record";
	}
}

function submit_add_detail($config)
{
	$categoryId = $_POST['categoryId'];
	$detail = $_POST['detail'];
	
	$time = mktime($_POST['detail_form_time_hour'], 
					$_POST['detail_form_time_minute'], 
					$_POST['detail_form_time_second'], 
					$_POST['detail_form_time_month'], 
					$_POST['detail_form_time_day'], 
					$_POST['detail_form_time_year']);	
	
	$amount = $_POST['amount'];
	$description = $_POST['description'];
	
	return add_detail($config, $categoryId, $detail, $time, $amount, $description);
}

function submit_edit_detail($config)
{
	$id = $_POST['id'];
	$categoryId = $_POST['categoryId'];
	$detail = $_POST['detail'];
	
	$time = mktime($_POST['detail_form_time_hour'], 
					$_POST['detail_form_time_minute'], 
					$_POST['detail_form_time_second'], 
					$_POST['detail_form_time_month'], 
					$_POST['detail_form_time_day'], 
					$_POST['detail_form_time_year']);
	
	$amount = $_POST['amount'];
	$description = $_POST['description'];
	
	return edit_detail($config, $id, $categoryId, $detail, $time, $amount, $description);
}

function submit_delete_detail($config)
{
	$id = $_POST['id'];
	return delete_detail($config, $id);
}

function confirmDeleteDetail($config)
{
	$detail = get_detail($config, $_GET['id']);
	
	$category = $detail['Category'];
	$detail_field = $detail['Detail'];
	$time = $detail['Time'];
	$amount = $detail['Amount'];
	$desc = $detail['Description'];
	
	echo "<p>Are you sure want to delete below record?</p>";
	echo "<p>Category: ".$category."<br/>";
	echo "Detail: ".$detail_field."<br/>";
	echo "Time: ".date("Y-m-d H:i",$time)."<br/>";
	echo "Amount: ".$amount."<br/>";
	echo "Description: ".$desc."</p>";
	
?>
<form action="detail.php" method="post">
	<input type="hidden" name="action_type" value="submit_delete"/>
	<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>"/>
	
	<table border="0">
		<tr>
			<td><a href="detail.php?action_type=list">No</a></td>
			<td><input type="submit" value="Yes" /></td>
		</tr>
	</table>
</form>
<?php
}

function show_add_detail_form($config)
{
	show_detail_form("add", $config, 0);
}

function show_edit_detail_form($config, $id)
{
	$detail = get_detail($config, $id);
	if($detail)
		show_detail_form("edit", $config, $detail);
	else
		echo "<p>Error: Can't get detail that has id = $id</p>";
}

function showCategoryListBox($category_list, $selected_category_id)
{
	$count = count($category_list);
	echo "<select name='categoryId'>";
	for($i=0; $i < $count; $i++)
	{
		$tag = "<option value='".$category_list[$i]['Id']."'";
		
		if($category_list[$i]['Id'] == $selected_category_id)
			$tag .= " selected='selected'";
		
		$tag .= ">".$category_list[$i]['Name']."</option>";
		echo $tag;
	}
	echo "</select>";
}

function show_detail_form($form_type, $config, $detail)
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
<input type="hidden" name="id" value="<?php echo $detail['Id']; ?>"/>
<?php } else {} ?>

<table border="0">
<tr>
	<td>Category:</td>
	<td>
<?php
		$category_list = get_category_list($config);
		$selected_category_id = -1;
		if($form_type == "edit")
			$selected_category_id = $detail['CategoryId'];
		showCategoryListBox($category_list, $selected_category_id);
?>
	</td>
</tr>
<tr>
	<td>Detail:</td>
	<td>
		<input type="text" name="detail"
			value="<?php if($form_type == 'edit') echo $detail['Detail'];?>"
		/>
	</td>
</tr>
<tr>
	<td>Time:</td>
	<td>
<?php
		if($form_type == 'edit')		
			showTimeBox("detail_form_time", $detail['Time']);
		else
			showTimeBox("detail_form_time", 0);
?>
	</td>
</tr>
<tr>
	<td>Amount:</td>
	<td>
		<input type="text" name="amount"
			value="<?php if($form_type == 'edit') echo $detail['Amount'];?>"
		/>
	</td>
</tr>
<tr>
	<td>Description:</td>
	<td>
		<textarea name="description" rows="5" cols="30"><?php if($form_type == "edit") echo $detail['Description'];?></textarea>
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
			$category_list = get_category_list($config);
			foreach($category_list as $cate)
			{
				echo "<input type='checkbox' name='category_filter[]' ";
				echo "value='".$cate["Id"]."' ";
				
				if(isset($filters['categories']))
				{
					foreach($filters['categories'] as $cate_filter)
						if($cate["Id"] == $cate_filter)
						{
							echo "checked='checked'";
							break;
						}
				}
								
				echo "/>".$cate["Name"]." ";
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
	show_available_money($config);
?>	
<table border="0" cellpadding="10">
	<tr>
		<td width="400"><?php show_add_detail_form($config); ?></td>
		<td><?php show_filter_form($config); ?></td>
	</tr>
</table>
<?php
	list_detail($config);
	break;
	
case "submit_add":
	$isSuccess = submit_add_detail($config);
	if($isSuccess)
		echo "<p>1 detail is added</p>";
	else
		echo "<p>CAN NOT add detail</p>";
		
	show_available_money($config);
	show_add_detail_form($config);
	list_detail($config);
	break;
	
case "edit":
	show_edit_detail_form($config, $_GET['id']);
	break;
	
case "submit_edit":
	$isSuccess = submit_edit_detail($config);
	if($isSuccess)
		echo "<p>1 detail is updated</p>";
	else
		echo "<p>CAN NOT update detail</p>";
		
	show_available_money($config);
	show_add_detail_form($config);
	list_detail($config);
	break;
	
case "delete":
	confirmDeleteDetail($config);
	break;
	
case "submit_delete":
	$isSuccess = submit_delete_detail($config);
	if($isSuccess)
		echo "<p>1 detail is deleted</p>";
	else
		echo "<p>CAN NOT delete detail</p>";
		
	show_available_money($config);
	show_add_detail_form($config);
	list_detail($config);
	break;
}

?>

</body>
</html>