<?php
class EditCategoryForm extends Form
{
	var $category;
	
	function EditCategoryForm($category)
	{
		parent::Form("image/edit_btn.png", "Edit Category", "editting_category.php", "Save");
		$this->category = $category;
	}
	
	function ShowTypeSelector()
	{
		echo "<tr>";
		echo "   <td class='title'>Type</td>";
		echo "   <td>";
		
		if($this->category->is_receipt)
		{
			echo "      <input type='radio' name='type' value='expenditure' />Payment";
			echo "      <input type='radio' name='type' value='receipt' checked='checked' />Receipt";			
		}
		else
		{
			echo "      <input type='radio' name='type' value='expenditure' checked='checked' />Payment";
			echo "      <input type='radio' name='type' value='receipt' />Receipt";
		}
					
		echo "   </td>";
		echo "</tr>";
	}
	
	function Show()
	{
		parent::BeginShow();
		echo "<input type='hidden' name='id' value='".$this->category->id."'/>";
		$this->ShowInputBox("name", "Name", $this->category->name);
		$this->ShowTypeSelector();
		$this->ShowDescriptionInput("description", "Description", $this->category->description);
		parent::EndShow();
	}
}
?>