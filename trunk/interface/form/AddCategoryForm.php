<?php
class AddCategoryForm extends Form
{
	function AddCategoryForm()
	{
		parent::Form("image/add_btn.png", "Add New Category", "adding_category.php", "Add");
	}
	
	function ShowTypeSelector()
	{
		echo "<tr>";
		echo "   <td class='title'>Type</td>";
		echo "   <td>";
		echo "      <input type='radio' name='type' value='expenditure' checked='checked' />Payment";
		echo "      <input type='radio' name='type' value='receipt' />Receipt";			
		echo "   </td>";
		echo "</tr>";
	}
	
	function Show()
	{
		parent::BeginShow();	
		$this->ShowInputBox("name", "Name");
		$this->ShowTypeSelector();
		$this->ShowDescriptionInput("description", "Description");
		parent::EndShow();
	}
}
?>