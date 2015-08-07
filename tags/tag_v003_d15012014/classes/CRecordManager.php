<?php
class CRecordManager
{
	static $data;
	static $count;
	static $filter;
	
	static function GetFilter()
	{
		return self::$filter;
	}
	
	static function SetFilter($filter)
	{
		self::$filter = $filter;
	}
	
	static function LoadData()
	{
		self::$data = 0;
		self::$count = 0;
		
		$sql = self::$filter->GetSelectCommand();
		$records = CDataManager::ExercuseQuery($sql);
		
		// Fetch data
		while($row = mysql_fetch_array($records))
		{
			if(!self::$data)
				self::$data = array();
				
			self::$data[self::$count] = new CRecord($row['Id'], 
											$row['CategoryId'],
											$row['Detail'],
											$row['Time'],
											$row['Amount'],
											$row['Description']);
			
			self::$count++;
		}
	}
	
	static function GetRecords()
	{
		self::LoadData();
		return self::$data;
	}
	
	static function GetNumberOfRecord()
	{
		self::LoadData();
		return self::$count;
	}
	
	static function GetRecordByIndex($index)
	{
		self::LoadData();
		return self::$data[$index];
	}
	
	static function GetRecordById($id)
	{
		if(!self::$filter)
			self::$filter = new CFilter($id, 0, 0, 0, 0, 0, 0, 0);
		self::LoadData();
		
		$i = 0;
		while($i < self::$count)
		{
			$record = self::$data[$i];
			if($record->GetId() == $id)
				return $record;				
			$i++;
		}
		return null;
	}
	
	static function AddRecord($record)
	{
		$sql = "INSERT INTO detail (CategoryId,Detail,Time,Amount,Description) 
				VALUES ('".$record->GetCategoryId()."',
						'".$record->GetDetail()."',
						".$record->GetTime().",
						".$record->GetAmount().",
						'".$record->GetDescription()."')";
		return CDataManager::ExercuseQuery($sql);
	}
	
	static function UpdateRecord($record)
	{
		$sql = "UPDATE detail 
				SET CategoryId = '".$record->GetCategoryId()."',
					Detail = '".$record->GetDetail()."', 
					Time = ".$record->GetTime().", 
					Amount = ".$record->GetAmount().", 
					Description = '".$record->GetDescription()."'
				WHERE Id = ".$record->GetId();
		return CDataManager::ExercuseQuery($sql);
	}
	
	static function DeleteRecord($id)
	{
		$sql = "DELETE FROM detail WHERE Id=$id";
		return CDataManager::ExercuseQuery($sql);
	}
	
	static function ClearAllRecords()
	{
		$sql = "DELETE FROM detail";
		if(!CDataManager::ExercuseQuery($sql))
			return 0;
		
		$sql = "ALTER TABLE detail AUTO_INCREMENT=1";
		return CDataManager::ExercuseQuery($sql);
	}
	
	static function CreateTable()
	{
		$sql = "CREATE TABLE detail(
					Id int NOT NULL AUTO_INCREMENT,
					CategoryId int NOT NULL,
					Detail varchar(255),
					Time int(10),
					Amount int,
					Description varchar(1024),
					PRIMARY KEY (Id)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		return CDataManager::ExercuseQuery($sql);
	}
	
	static function DropTable()
	{
		$sql = "DROP TABLE IF EXISTS detail";
		return CDataManager::ExercuseQuery($sql);
	}
}
?>