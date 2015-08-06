<?php
function showTimeBox($prefix_name, $edit_time)
{	
	if($edit_time)
	{
		$time = $edit_time;
	}
	else
	{
		$time = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));		
	}
	
	//Hour
	echo "<input type='text' name='".$prefix_name."_hour' value='".date("H", $time)."' maxlength='2' size='2'/>";
	
	//Minute
	echo "<input type='text' name='".$prefix_name."_minute' value='".date("i", $time)."' maxlength='2' size='2'/>";
	
	//Second
	if($edit_time)
		echo "<input name='".$prefix_name."_second' value='".date("s", $time)."' maxlength='2' size='2'/>";
	else
		echo "<input type='hidden' name='".$prefix_name."_second' value='0'/>";

	//Day
	echo "<select name='".$prefix_name."_day'>";
	for($i=1; $i<=31; $i++)
	{
		$day_tag = "<option value='$i'";
		if($i == date("d", $time))
			$day_tag .= " selected='selected'";
		$day_tag .= ">$i</option>";
		echo $day_tag;
	}
	echo "</select>";

	//Month
	echo "<select name='".$prefix_name."_month'>";
	for($i=1; $i<=12; $i++)
	{
		$month_tag = "<option value='$i'";
		if($i == date("m", $time))
			$month_tag .= " selected='selected'";
		$month_tag .= ">$i</option>";
		echo $month_tag;
	}
	echo "</select>";

	//Year
	$fromYear = 1988;
	$toYear = 2020;
	echo "<select name='".$prefix_name."_year'>";
	for($i=$fromYear; $i<=$toYear; $i++)
	{
		$year_tag = "<option value='$i'";
		if($i == date("Y", $time))
			$year_tag .= " selected='selected'";
		$year_tag .= ">$i</option>";
		echo $year_tag;
	}
	echo "</select>";
}
?>