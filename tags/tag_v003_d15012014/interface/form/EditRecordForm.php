<?php
class EditRecordForm extends Form
{
	var $categories;
	var $record;
	
	function EditRecordForm($icon, $name, $target_page, $submit_button_name, $categories, $record)
	{
		parent::Form($icon, $name, $target_page, $submit_button_name);
		$this->categories = $categories;
		$this->record = $record;
	}
	
	function ShowCategoryListBox()
	{
		echo "<tr>";
		echo "<td class='title'>Category:</td>";
		echo "<td><select name='categoryId'>";
		foreach($this->categories as $category)
		{
			$tag = "<option value='".$category->GetId()."'";
		
			if($category->GetId() == $this->record->GetCategoryId())
				$tag .= " selected='selected'";
			
			$tag .= ">".$category->GetName()."</option>";
			echo $tag;
		}
		echo "</select></td>";
		echo "</tr>";
	}
	
	function ShowAmountBox($id, $name, $default_value=0)
	{
		echo "<tr>";
		echo "	<td class='title'>$name:</td>";
		echo "	<td>";
		
		if($default_value >= 1000)
		{
			echo "		<input type='text' name='$id' value='".($default_value / 1000)."' size='5'/><!--
									--><select name='unit'>
										<option value='1000_vnd' selected='selected'>x1000 VND</option>
										<option value='vnd'>x1 VND</option>
									</select>";
		}
		else
		{
			echo "		<input type='text' name='$id' value='".$default_value."' size='5'/><!--
									--><select name='unit'>
										<option value='1000_vnd'>x1000 VND</option>
										<option value='vnd' selected='selected'>x1 VND</option>
									</select>";
		}
		
		echo "	</td>";
		echo "</tr>";
	}
	
	function Show()
	{
		parent::BeginShow();
		echo "<input type='hidden' name='id' value='".$this->record->GetId()."'/>";
		$this->ShowCategoryListBox();
		$this->ShowInputBox("detail", "Detail", $this->record->GetDetail());
		$this->showDateInput("date", "Date", $this->record->GetTime());
		$this->showTimeInput("time", "Time", $this->record->GetTime());
		$this->ShowAmountBox("amount", "Amount", $this->record->GetAmount());
		parent::EndShow();
	}
}
?>