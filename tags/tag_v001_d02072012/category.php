<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Finance - Category Manager</title>
</head>
<body>

<p><a href="detail.php">Detail</a> | <a href="setting.php">Setting</a></p>

<h3>Category Manager</h3>

<?php
require("config.php");
require("common.php");
require("data_category.php");

//*********************
// FUNCTIONS
//*********************
function list_category($config)
{
	$cate_list = get_category_list($config);
	if($cate_list)
	{
		$num = count($cate_list);
		if($num < 2)
			echo "<p>There is $num category</p>";
		else
			echo "<p>There are $num categories</p>";
		
		echo "<table border='1' cellspacing='0'>";
		echo "<tr>";
		echo "	<th>ID</th>";
		echo "	<th>Name</th>";
		echo "	<th>Receipt</th>";
		echo "	<th>Description</th>";
		echo "	<th colspan='2'>Action</th>";
		echo "</tr>";
		
		for($i = 0; $i < $num; $i++)
		{
			echo "<tr>";
			echo "	<td>".$cate_list[$i]['Id']."</td>";
			echo "	<td>".$cate_list[$i]['Name']."</td>";
			
			if($cate_list[$i]['Receipt'])
				echo "	<td>Yes</td>";
			else
				echo "	<td>&nbsp;</td>";
			
			echo "	<td>".$cate_list[$i]['Description']."&nbsp;</td>";
			echo "	<td><a href='category.php?action_type=edit&id=".$cate_list[$i]['Id']."'>Edit</a></td>";
			echo "	<td><a href='category.php?action_type=delete&id=".$cate_list[$i]['Id']."'>Delete</a></td>";
			echo "<tr/>";
		}
		
		echo "</table>";
	}
	else
	{
		echo "There is no any category";
	}
}

function submit_add_category($config)
{
	$name = $_POST['name'];
	$receipt = ($_POST['type'] == "receipt") ? 1 : 0;
	$description = $_POST['description'];
	
	return add_category($config, $name, $receipt, $description);
}

function submit_edit_category($config)
{
	$id = $_POST['id'];
	$name = $_POST['name'];
	$receipt = ($_POST['type'] == "receipt") ? 1 : 0;
	$description = $_POST['description'];
	
	return edit_category($config, $id, $name, $receipt, $description);
}

function submit_delete_category($config)
{
	$id = $_POST['id'];
	return delete_category($config, $id);
}

function confirmDeleteCategory($config)
{
	$category = get_category($config, $_GET['id']);
	
	$name = $category['Name'];
	$receipt = $category['Receipt'] ? "Yes" : "No";
	$desc = $category['Description'];
	
	echo "<p>Are you sure want to delete below category?</p>";
	echo "<p>Name: ".$name."<br/>";
	echo "Receipt: ".$receipt."<br/>";
	echo "Description: ".$desc."</p>";
	
?>
<form action="category.php" method="post">
	<input type="hidden" name="action_type" value="submit_delete"/>
	<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>"/>
	
	<table border="0">
		<tr>
			<td><a href="category.php?action_type=list">No</a></td>
			<td><input type="submit" value="Yes" /></td>
		</tr>
	</table>
</form>
<?php
}

function show_add_category_form()
{
	show_category_form("add", 0, 0, 0, 0);
}

function show_edit_category_form($config, $id)
{
	$category = get_category($config, $id);
	if($category)
		show_category_form("edit", $category['Id'], $category['Name'], $category['Receipt'], $category['Description']);
	else
		echo "<p>Error: Can't get category that has id = $id</p>";
}

function show_category_form($form_type, $id, $name, $receipt, $description)
{
?>

<?php if($form_type == "add") {?>
<h4>Add new category</h4>
<?php } else if($form_type == "edit") {?>
<h4>Edit category</h4>
<?php } else {} ?>

<form action="category.php" method="post">

<!-- Request type -->
<?php if($form_type == "add") {?>
<input type="hidden" name="action_type" value="submit_add"/>
<?php } else if($form_type == "edit") {?>
<input type="hidden" name="action_type" value="submit_edit"/>
<input type="hidden" name="id" value="<?php echo $id; ?>"/>
<?php } else {} ?>

<table border="0">
<tr>
	<td>Name:</td>
	<td>
		<input type="text" name="name"
			value="<?php if($form_type == 'edit') echo $name;?>"
		/>
	</td>
</tr>
<tr>
	<td>Type:</td>
	<td>
		<input type="radio" name="type" value="expenditure" 
			<?php 
				if($form_type == "add" 
					|| ($form_type == "edit" && $receipt == 0))
					echo "checked='checked'"; 
			?>
		/>Payment
		<input type="radio" name="type" value="receipt" 
			<?php 
				if($form_type == "edit" && $receipt == 1)
					echo "checked='checked'";
			?>
		/>Receipt
	</td>
</tr>
<tr>
	<td>Description:</td>
	<td>
		<textarea name="description" rows="5" cols="30"><?php if($form_type == "edit") echo $description;?></textarea>
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
<table>
</form>
<?php
}



//*********************
// MAIN PROCESS
//*********************
$action_type = "list";
if(isset($_REQUEST['action_type']))
	$action_type = $_REQUEST['action_type'];
	
switch($action_type)
{
case "list":
	list_category($config);
	show_add_category_form();
	break;
	
case "submit_add":
	$isSuccess = submit_add_category($config);
	if($isSuccess)
		echo "<p>1 category is added</p>";
	else
		echo "<p>CAN NOT add category</p>";
		
	list_category($config);
	show_add_category_form();
	break;
	
case "edit":
	show_edit_category_form($config, $_GET['id']);
	break;
	
case "submit_edit":
	$isSuccess = submit_edit_category($config);
	if($isSuccess)
		echo "<p>1 category is updated</p>";
	else
		echo "<p>CAN NOT update category</p>";
		
	list_category($config);
	show_add_category_form();
	break;
	
case "delete":
	confirmDeleteCategory($config);
	break;
	
case "submit_delete":
	$isSuccess = submit_delete_category($config);
	if($isSuccess)
		echo "<p>1 category is deleted</p>";
	else
		echo "<p>CAN NOT delete category</p>";
		
	list_category($config);
	show_add_category_form();
	break;
}

?>

</body>
</html>