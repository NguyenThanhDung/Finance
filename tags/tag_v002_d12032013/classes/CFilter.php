<?php
class CFilter
{
	var $record_id;
	var $category_ids;
	var $detail;
	var $from_date;
	var $to_date;
	var $from_amount;
	var $to_amount;
	var $description;
	
	function CFilter($record_id, $category_ids, $detail, $from_date, $to_date, 
					$from_amount, $to_amount, $description)
	{
		$this->record_id = $record_id;
		$this->category_ids = $category_ids;
		$this->detail = $detail;
		$this->from_date = $from_date;
		$this->to_date = $to_date;
		$this->from_amount = $from_amount;
		$this->to_amount = $to_amount;
		$this->description = $description;
	}
	
	function GetSelectCommand()
	{
		$sql = "SELECT * FROM detail ";
		$sql .= $this->GetWhereClauseCommand();
		$sql .= "ORDER BY Time DESC";
		return $sql;
	}
	
	function GetWhereClauseCommand()
	{
		$where_clause = null;
		
		if($this->record_id)
		{
			if($where_clause)
				$where_clause .= "AND ";
			else
				$where_clause .= "WHERE ";
				
			$where_clause .= "(Id = ".$this->record_id.") ";
		}
		
		if($this->category_ids)
		{
			$cate_count = count($this->category_ids);
		
			if($where_clause)
				$where_clause .= "AND ";
			else
				$where_clause .= "WHERE ";
			
			$where_clause .= "(CategoryId IN (";
			for($i = 0; $i < $cate_count; $i++)
			{
				$where_clause .= $this->category_ids[$i];
				if($i < $cate_count - 1)
					$where_clause .= ",";
			}
			$where_clause .= ")) ";
		}
		
		if($this->detail)
		{
			if($where_clause)
				$where_clause .= "AND ";
			else
				$where_clause .= "WHERE ";
				
			$where_clause .= "(Detail LIKE '%".$this->detail."%') ";
		}
		
		if($this->from_date)
		{		
			if($where_clause)
				$where_clause .= "AND ";
			else
				$where_clause .= "WHERE ";
			
			$where_clause .= "Time >= ".$this->from_date." ";
		}
		
		if($this->to_date)
		{		
			if($where_clause)
				$where_clause .= "AND ";
			else
				$where_clause .= "WHERE ";
			
			$where_clause .= "Time <= ".$this->to_date." ";
		}
		
		if($this->from_amount)
		{		
			if($where_clause)
				$where_clause .= "AND ";
			else
				$where_clause .= "WHERE ";
			
			$where_clause .= "Amount >= ".$this->from_amount." ";
		}
		
		if($this->to_amount)
		{		
			if($where_clause)
				$where_clause .= "AND ";
			else
				$where_clause .= "WHERE ";
			
			$where_clause .= "Amount <= ".$this->to_amount." ";
		}
		
		if($this->description)
		{
			if($where_clause)
				$where_clause .= "AND ";
			else
				$where_clause .= "WHERE ";
				
			$where_clause .= "(Description LIKE '%".$this->description."%') ";
		}	
		
		return $where_clause;
	}
	
	function IsFiltering()
	{		
		if($this->record_id
			|| $this->category_ids
			|| $this->detail
			|| $this->from_date
			|| $this->to_date
			|| $this->from_amount
			|| $this->to_amount
			|| $this->description)
			return 1;
		return 0;
	}
	
	function GetCategoryIds()
	{
		return $this->category_ids;
	}
	
	function SetCategoryIds($category_ids)
	{
		$this->category_ids = $category_ids;
	}
	
	function GetDetail()
	{
		return $this->detail;
	}
	
	function SetDetail($detail)
	{
		$this->detail = $detail;
	}
	
	function GetFromDate()
	{
		return $this->from_date;
	}
	
	function SetFromDate($from_date)
	{
		$this->from_date = $from_date;
	}
	
	function GetToDate()
	{
		return $this->to_date;
	}
	
	function SetToDate($to_date)
	{
		$this->to_date = $to_date;
	}
	
	function GetFromAmount()
	{
		return $this->from_amount;
	}
	
	function SetFromAmount($from_amount)
	{
		$this->from_amount = $from_amount;
	}
	
	function GetToAmount()
	{
		return $this->to_amount;
	}
	
	function SetToAmount($to_amount)
	{
		$this->to_amount = $to_amount;
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