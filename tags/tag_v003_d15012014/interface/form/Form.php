<?php
class Form
{
	var $icon;
	var $name;
	var $target_page;
	var $submit_button_name;
	
	function Form($icon, $name, $target_page, $submit_button_name)
	{
		$this->icon = $icon;
		$this->name = $name;
		$this->target_page = $target_page;
		$this->submit_button_name = $submit_button_name;
	}
	
	function ShowInputBox($id, $name, $default_value="")
	{
		echo "<tr>";
		echo "	<td class='title'>$name:</td>";
		echo "	<td>";
		echo "		<input type='text' name='$id' value='$default_value'/>";
		echo "	</td>";
		echo "</tr>";
	}
	
	function showDateInput($id, $name, $default_value=0)
	{
		if($default_value)
		{
			$time = $default_value;
		}
		else
		{
			$time = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));		
		}
		
		echo "<tr>";
		echo "	<td class='title'>$name:</td>";
		echo "	<td>";
		
		//Day
		echo "		<select name='".$id."_day'>";
		for($i=1; $i<=31; $i++)
		{
			$day_tag = "<option value='$i'";
			if($i == date("d", $time))
				$day_tag .= " selected='selected'";
			$day_tag .= ">$i</option>";
			echo $day_tag;
		}
		echo "		</select>";
		
		//Month
		echo "		<select name='".$id."_month'>";
		for($i=1; $i<=12; $i++)
		{
			$month_tag = "<option value='$i'";
			if($i == date("m", $time))
				$month_tag .= " selected='selected'";
			$month_tag .= ">$i</option>";
			echo $month_tag;
		}
		echo "		</select>";
		
		//Year
		echo "		<select name='".$id."_year'>";
		for($i=2010; $i<=2020; $i++)
		{
			$year_tag = "<option value='$i'";
			if($i == date("Y", $time))
				$year_tag .= " selected='selected'";
			$year_tag .= ">$i</option>";
			echo $year_tag;
		}
		echo "		</select>";
		
		echo "	</td>";
		echo "</tr>";
	}
	
	function showTimeInput($id, $name, $default_value=0)
	{
		if($default_value)
		{
			$time = $default_value;
		}
		else
		{
			$time = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));		
		}
		
		echo "<tr>";
		echo "	<td class='title'>$name:</td>";
		echo "	<td>";
		
		echo "<input type='text' name='".$id."_hour' size='1' value='".date("H", $time)."'/>:<!--
		--><input type='text' name='".$id."_minute' size='1' value='".date("i", $time)."'/>";
		
		echo "	</td>";
		echo "</tr>";
	}
	
	function ShowDateAndTimeInput($id, $name, $default_value=0)
	{
		if($default_value)
		{
			$time = $default_value;
		}
		else
		{
			$time = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));		
		}
		
		echo "<tr>";
		echo "	<td class='title'>$name</td>";
		echo "	<td>";
		
		// Time
		echo "<input type='text' name='".$id."_hour' size='1' value='".date("H", $time)."'/>:<!--";
		echo "--><input type='text' name='".$id."_minute' size='1' value='".date("i", $time)."'/>";
		
		// Day
		echo "<select name='".$id."_day'>";
		for($i=1; $i<=31; $i++)
		{
			$day_tag = "<option value='$i'";
			if($i == date("d", $time))
				$day_tag .= " selected='selected'";
			$day_tag .= ">$i</option>";
			echo $day_tag;
		}
		echo "</select><!--";
		
		// Month
		echo "--><select name='".$id."_month'>";
		for($i=1; $i<=12; $i++)
		{
			$month_tag = "<option value='$i'";
			if($i == date("m", $time))
				$month_tag .= " selected='selected'";
			$month_tag .= ">$i</option>";
			echo $month_tag;
		}
		echo "</select><!--";
		
		//Year
		echo "--><select name='".$id."_year'>";
		for($i=2014; $i<=2024; $i++)
		{
			$year_tag = "<option value='$i'";
			if($i == date("Y", $time))
				$year_tag .= " selected='selected'";
			$year_tag .= ">$i</option>";
			echo $year_tag;
		}
		echo "</select>";
		
		echo "	</td>";
		echo "</tr>";
	}
	
	function ShowDescriptionInput($id, $name, $default_value="")
	{
		echo "<tr>";
		echo "   <td class='title'>$name</td>";
		echo "   <td>";
		echo "      <textarea name='$id' rows='5' cols='17'>&nbsp;</textarea>";
		echo "   </td>";
		echo "</tr>";
	}
	
	function ShowSubmitButton()
	{
		echo "<tr>";
		echo "<td>&nbsp;</td>";
		echo "<td><input type='submit' value='$this->submit_button_name' /></td>";
		echo "</tr>";
	}
	
	function BeginShow()
	{
		echo "<img class='form_icon' src='".$this->icon."' /><h3 class='box_title'>".$this->name."</h3>";
		echo "<form action='$this->target_page' method='post'>";	
		echo "<table>";
	}
	
	function EndShow()
	{
		$this->ShowSubmitButton();
		echo "</table>";
		echo "</form>";
	}
}
?>