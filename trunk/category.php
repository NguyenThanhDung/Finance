<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Finance - Category Manager</title>
</head>
<body>

<p><a href="detail.php">Detail</a> | <a href="setting.php">Setting</a></p>

<h3>Category Manager</h3>

<?php
date_default_timezone_set("Asia/Saigon");

require("config.php");

function __autoload($class_name) 
{
    include "classes/".$class_name . '.php';
}

//*********************
// FUNCTIONS
//*********************
function list_category()
{
	$categories = CCategoryManager::GetInstance()->GetAllCategories();
	if($categories)
	{
		$num = CCategoryManager::GetInstance()->GetNumberOfCategory();
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
		
		foreach($categories as $category)
		{
			echo "<tr>";
			echo "	<td>".$category->GetId()."</td>";
			echo "	<td>".$category->GetName()."</td>";
			
			if($category->IsReceipt())
				echo "	<td>Yes</td>";
			else
				echo "	<td>&nbsp;</td>";
			
			echo "	<td>".$category->GetDescription()."&nbsp;</td>";
			echo "	<td><a href='category.php?action_type=edit&id=".$category->GetId()."'>Edit</a></td>";
			echo "	<td><a href='category.php?action_type=delete&id=".$category->GetId()."'>Delete</a></td>";
			echo "<tr/>";
		}
		
		echo "</table>";
	}
	else
	{
		echo "There is no any category";
	}
}

function submit_add_category()
{
	$name = $_POST['name'];
	$receipt = ($_POST['type'] == "receipt") ? 1 : 0;
	$description = $_POST['description'];
	
	$category = new CCategory(0, $name, $receipt, $description);
	return CCategoryManager::GetInstance()->AddCategory($category);
}

function submit_edit_category()
{
	$id = $_POST['id'];
	$name = $_POST['name'];
	$receipt = ($_POST['type'] == "receipt") ? 1 : 0;
	$description = $_POST['description'];
	
	$category = new CCategory($id, $name, $receipt, $description);
	return CCategoryManager::GetInstance()->UpdateCategory($category);
}

function submit_delete_category()
{
	return CCategoryManager::GetInstance()->DeleteCategory($_POST['id']);
}

function confirmDeleteCategory()
{
	$category = CCategoryManager::GetInstance()->GetCategoryById($_GET['id']);
	
	echo "<p>Are you sure want to delete below category?</p>";
	echo "<p>Name: ".$category->GetName()."<br/>";
	echo "Receipt: ".($category->IsReceipt() ? "Yes" : "No")."<br/>";
	echo "Description: ".$category->GetDescription()."</p>";
	
?>
<form action="category.php" method="post">
	<input type="hidden" name="action_type" value="submit_delete"/>
	<input type="hidden" name="id" value="<?php echo $category->GetId(); ?>"/>
	
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
	show_category_form("add", 0);
}

function show_edit_category_form($id)
{
	show_category_form("edit", $id);
}

function show_category_form($form_type, $id)
{
	$category = 0;
	if($form_type == "edit")
	{
		$category = CCategoryManager::GetInstance()->GetCategoryById($id);
	}
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
<input type="hidden" name="id" value="<?php echo $category->GetId(); ?>"/>
<?php } else {} ?>

<table border="0">
<tr>
	<td>Name:</td>
	<td>
		<input type="text" name="name"
			value="<?php if($form_type == 'edit') echo $category->GetName();?>"
		/>
	</td>
</tr>
<tr>
	<td>Type:</td>
	<td>
		<input type="radio" name="type" value="expenditure" 
			<?php 
				if($form_type == "add" 
					|| ($form_type == "edit" && $category->IsReceipt() == 0))
					echo "checked='checked'"; 
			?>
		/>Payment
		<input type="radio" name="type" value="receipt" 
			<?php 
				if($form_type == "edit" && $category->IsReceipt() == 1)
					echo "checked='checked'";
			?>
		/>Receipt
	</td>
</tr>
<tr>
	<td>Description:</td>
	<td>
		<textarea name="description" rows="5" cols="30"><?php if($form_type == "edit") echo $category->GetDescription();?></textarea>
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
	list_category();
	show_add_category_form();
	break;
	
case "submit_add":
	$isSuccess = submit_add_category();
	if($isSuccess)
		echo "<p>1 category is added</p>";
	else
		echo "<p>CAN NOT add category</p>";
		
	list_category();
	show_add_category_form();
	break;
	
case "edit":
	show_edit_category_form($_GET['id']);
	break;
	
case "submit_edit":
	$isSuccess = submit_edit_category();
	if($isSuccess)
		echo "<p>1 category is updated</p>";
	else
		echo "<p>CAN NOT update category</p>";
		
	list_category();
	show_add_category_form();
	break;
	
case "delete":
	confirmDeleteCategory();
	break;
	
case "submit_delete":
	$isSuccess = submit_delete_category();
	if($isSuccess)
		echo "<p>1 category is deleted</p>";
	else
		echo "<p>CAN NOT delete category</p>";
		
	list_category();
	show_add_category_form();
	break;
}

?>

</body>
</html>