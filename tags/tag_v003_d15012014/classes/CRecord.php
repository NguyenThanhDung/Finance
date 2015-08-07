<?php
class CRecord
{
	var $id;
	var $category_id;
	var $detail;
	var $time;
	var $amount;
	var $description;
	
	function CRecord($id, $category_id, $detail, $time, $amount, $description)
	{
		$this->id = $id;
		$this->category_id = $category_id;
		$this->detail = $detail;
		$this->time = $time;
		$this->amount = $amount;
		$this->description = $description;
	}
	
	function GetId()
	{
		return $this->id;
	}
	
	function SetId($id)
	{
		$this->id = $id;
	}
	
	function GetCategoryId()
	{
		return $this->category_id;
	}
	
	function SetCategoryId($category_id)
	{
		$this->category_id = $category_id;
	}
	
	function GetCategory()
	{
		return CCategoryManager::GetCategoryById($this->category_id);
	}
	
	function GetDetail()
	{
		return $this->detail;
	}
	
	function SetDetail($detail)
	{
		$this->detail = $detail;
	}
	
	function GetTime()
	{
		return $this->time;
	}
	
	function SetTime($time)
	{
		$this->time = $time;
	}
	
	function GetAmount()
	{
		return $this->amount;
	}
	
	function SetAmount($amount)
	{
		$this->amount = $amount;
	}
	
	function GetDescription()
	{
		return $this->description;
	}
	
	function SetDescription($description)
	{
		$this->description = $description;
	}
}
?>