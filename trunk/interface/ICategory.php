<?php
function show_categories()
{
	$categories = CCategoryManager::GetAllCategories();
	if($categories)
	{
?>
<div class="table">
	<table class="list">
		<tr>
			<th>Name</th>
			<th>Is Receipt</th>
			<th>Description</th>
			<th>Edit</th>
			<th>Delete</th>
		</tr>
		
<?php		
		for($i = 0; $i < count($categories); $i++)
		{
			$category = $categories[$i];
			
			if($i % 2 == 0)
				echo "<tr class='alt'>";
			else
				echo "<tr>";
			echo "	<td>".$category->GetName()."</td>";
			
			if($category->IsReceipt())
				echo "<td class='image_cell'><img src='image/tick.png' /></td>";
			else
				echo "<td>&nbsp;</td>";
			
			echo "	<td>".$category->GetDescription()."&nbsp;</td>";
			echo "	<td class='image_cell'><a href='edit_category.php?id=".$category->GetId()."'><img src='image/edit_btn.png' /></a></td>";
			echo "	<td class='image_cell'><a href='deleting_category.php?id=".$category->GetId()."'><img src='image/delete_btn.png' /></a></td>";
			echo "<tr/>";
		} 
?>
	</table>
</div> <!-- End of table -->
<?php
	}
	else
	{
		echo "There is no any record";
	}
}

function show_add_category_form()
{
	$addCategoryForm = new AddCategoryForm();
	$addCategoryForm->Show();
}

function submit_add_category($name, $receipt, $description)
{
	$category = new CCategory(0, $name, $receipt, $description);
	return CCategoryManager::AddCategory($category);
}

function show_edit_category_form($id)
{
	$category = CCategoryManager::GetCategoryById($id);
	if($category)
	{
		$editCategoryForm = new EditCategoryForm($category);
		$editCategoryForm->Show();
		return 1;
	}
	else
	{
		return 0;
	}
}

function submit_edit_category($id, $name, $receipt, $description)
{
	$category = new CCategory($id, $name, $receipt, $description);
	return CCategoryManager::UpdateCategory($category);
}

function submit_delete_category($id)
{
	return CCategoryManager::DeleteCategory($id);
}