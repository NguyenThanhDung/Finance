<?php
class AddRecordForm extends Form
{
	var $categories;
	
	function AddRecordForm($icon, $name, $target_page, $submit_button_name, $categories)
	{
		parent::Form($icon, $name, $target_page, $submit_button_name);
		$this->categories = $categories;
	}
	
	function ShowCategoryListBox()
	{
		echo "<tr>";
		echo "<td class='title'>Category:</td>";
		echo "<td><select name='categoryId'>";
		foreach($this->categories as $category)
		{
			echo "<option value='".$category->GetId()."'>".$category->GetName()."</option>";
		}
		echo "</select></td>";
		echo "</tr>";
	}
	
	function ShowAmountBox($id, $name, $default_value="")
	{
		echo "<tr>";
		echo "	<td class='title'>$name:</td>";
		echo "	<td>";
		
		echo "		<input type='text' name='$id' value='$default_value' size='5'/><!--
								--><select name='unit'>
									<option value='1000_vnd'>x1000 VND</option>
									<option value='vnd'>x1 VND</option>
								</select>";
		
		echo "	</td>";
		echo "</tr>";
	}
	
	function Show()
	{
		parent::BeginShow();
		$this->ShowCategoryListBox();
		$this->ShowInputBox("detail", "Detail");
		$this->showDateInput("date", "Date");
		$this->showTimeInput("time", "Time");
		$this->ShowAmountBox("amount", "Amount");
		parent::EndShow();
	}
}
?>