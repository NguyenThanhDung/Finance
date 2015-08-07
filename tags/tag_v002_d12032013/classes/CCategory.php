<?php
class CCategory
{
	var $id;
	var $name;
	var $is_receipt;
	var $description;
	
	function CCategory($id, $name, $is_receipt, $description)
	{
		$this->id = $id;
		$this->name = $name;
		$this->is_receipt = $is_receipt;
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
	
	function GetName()
	{
		return $this->name;
	}
	
	function SetName($name)
	{
		$this->name = $name;
	}
	
	function IsReceipt()
	{
		return $this->is_receipt;
	}
	
	function SetReceipt($is_receipt)
	{
		$this->is_receipt = $is_receipt;
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