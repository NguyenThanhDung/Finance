<?php
class CRecordManager
{
	static $instance;
	var $data;
	var $count;
	var $filter;
	
	static function GetInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new CRecordManager();
		}
		return self::$instance;
	}
	
	function CRecordManager()
	{
		$this->data = 0;
		$this->count = 0;
	}
	
	function GetFilter()
	{
		return $this->filter;
	}
	
	function SetFilter($filter)
	{
		$this->filter = $filter;
	}
	
	function LoadData()
	{
		$sql = $this->filter->GetSelectCommand();
		$records = CDataManager::GetInstance()->ExercuseQuery($sql);
		
		// Fetch data
		while($row = mysql_fetch_array($records))
		{
			if(!$this->data)
				$this->data = array();
				
			$this->data[$this->count] = new CRecord($row['Id'], 
											$row['CategoryId'],
											$row['Detail'],
											$row['Time'],
											$row['Amount'],
											$row['Description']);
			
			$this->count++;
		}
	}
	
	function GetRecords()
	{
		if(!$this->data)
		{
			$this->LoadData();
		}
		return $this->data;
	}
	
	function GetNumberOfRecord()
	{
		if(!$this->data)
		{
			$this->LoadData();
		}
		return $this->count;
	}
	
	function GetRecordByIndex($index)
	{
		if(!$this->data)
		{
			$this->LoadData();
		}
		return $this->data[$index];
	}
	
	function GetRecordById($id)
	{
		if(!$this->data)
		{
			if(!$this->filter)
				$this->filter = new CFilter($id, 0, 0, 0, 0, 0, 0, 0);
			$this->LoadData();
		}
		
		$i = 0;
		while($i < $this->count)
		{
			$record = $this->data[$i];
			if($record->GetId() == $id)
				return $record;				
			$i++;
		}
		return null;
	}
	
	function AddRecord($record)
	{
		$sql = "INSERT INTO detail (CategoryId,Detail,Time,Amount,Description) 
				VALUES ('".$record->GetCategoryId()."',
						'".$record->GetDetail()."',
						".$record->GetTime().",
						".$record->GetAmount().",
						'".$record->GetDescription()."')";
		return CDataManager::GetInstance()->ExercuseQuery($sql);
	}
	
	function UpdateRecord($record)
	{
		$sql = "UPDATE detail 
				SET CategoryId = '".$record->GetCategoryId()."',
					Detail = '".$record->GetDetail()."', 
					Time = ".$record->GetTime().", 
					Amount = ".$record->GetAmount().", 
					Description = '".$record->GetDescription()."'
				WHERE Id = ".$record->GetId();
		return CDataManager::GetInstance()->ExercuseQuery($sql);
	}
	
	function DeleteRecord($id)
	{
		$sql = "DELETE FROM detail WHERE Id=$id";
		return CDataManager::GetInstance()->ExercuseQuery($sql);
	}
	
	function ClearAllRecords()
	{
		$sql = "DELETE FROM detail";
		if(!CDataManager::GetInstance()->ExercuseQuery($sql))
			return 0;
		
		$sql = "ALTER TABLE detail AUTO_INCREMENT=1";
		return CDataManager::GetInstance()->ExercuseQuery($sql);
	}
}
?>