<?php
class CSettingManager
{
	static $data = 0;
	static $count = 0;
	
	const VERSION = "Version";
	const INITIAL_MONEY = "InitialMoney";
	const LAST_CHECK_TIME = "LastCheckTime";
	
	static function LoadData()
	{
		self::$data = 0;
		self::$count = 0;
		
		$sql = "SELECT * FROM setting";		
		$settings = CDataManager::ExercuseQuery($sql);
		
		// Fetch data
		while($row = mysql_fetch_array($settings))
		{
			if(!self::$data)
				self::$data = array();
			
			self::$data[self::$count]['Name'] = $row['Name'];
			self::$data[self::$count]['Value'] = $row['Value'];
			self::$data[self::$count]['Description'] = $row['Description'];
				
			self::$count++;
		}
	}
	
	
	static function GetSetting($name)
	{
		self::LoadData();
		
		$i = 0;
		while($i < self::$count)
		{
			if(self::$data[$i]["Name"] == $name)
			{
				return self::$data[$i]["Value"];
			}
			$i++;
		}
		return null;
	}
	
	static function SaveSetting($initial_money, $last_check_time)
	{
		DLOG("SaveSetting($initial_money, $last_check_time)");

		$sql = "UPDATE setting
				SET Value = '$initial_money'
				WHERE name = '".self::INITIAL_MONEY."'";
		$result = CDataManager::ExercuseQuery($sql);
		
		$sql = "UPDATE setting
				SET Value = '$last_check_time'
				WHERE name = '".self::LAST_CHECK_TIME."'";
		$result &= CDataManager::ExercuseQuery($sql);
		
		return $result;
	}
	
	static function CreateTable()
	{
		$sql = "CREATE TABLE setting(
					Name varchar(100) NOT NULL,
					Value varchar(255) NOT NULL,
					Description varchar(1024),
					PRIMARY KEY (Name)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		if(!CDataManager::ExercuseQuery($sql))
			return 0;
			
		$sql = "INSERT INTO setting (Name,Value,Description) 
				VALUES ('Version','0.0.2','Version of finance product')";
		if(!CDataManager::ExercuseQuery($sql))
			return 0;
		
		$sql = "INSERT INTO setting (Name,Value,Description) 
				VALUES ('InitialMoney','0','The amount of money as the first time using the finance product')";
		if(!CDataManager::ExercuseQuery($sql))
			return 0;
			
		$sql = "INSERT INTO setting (Name,Value,Description) 
				VALUES ('LastCheckTime','577269000','Last time check the account')";
		return CDataManager::ExercuseQuery($sql);
	}
	
	static function DropTable()
	{
		$sql = "DROP TABLE IF EXISTS setting";
		return CDataManager::ExercuseQuery($sql);
	}
}
?>