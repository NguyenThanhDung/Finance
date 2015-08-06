<?php
class CSettingManager
{
	static $instance = 0;
	var $data = 0;
	var $count = 0;
	
	const VERSION = "Version";
	const INITIAL_MONEY = "InitialMoney";
	const LAST_CHECK_TIME = "LastCheckTime";
	
	static function GetInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new CSettingManager();
		}
		return self::$instance;
	}
	
	function CSettingManager()
	{
		$this->LoadData();
	}
	
	function LoadData()
	{
		$sql = "SELECT * FROM setting";		
		$settings = CDataManager::GetInstance()->ExercuseQuery($sql);
		
		// Fetch data
		while($row = mysql_fetch_array($settings))
		{
			if(!$this->data)
				$this->data = array();
			
			$this->data[$this->count]['Name'] = $row['Name'];
			$this->data[$this->count]['Value'] = $row['Value'];
			$this->data[$this->count]['Description'] = $row['Description'];
				
			$this->count++;
		}
	}
	
	function SaveData()
	{
		$sql = "UPDATE setting 
				SET Value = '".$this->GetSetting(self::INITIAL_MONEY)."'
				WHERE name = '".self::INITIAL_MONEY."'";
		$result = CDataManager::GetInstance()->ExercuseQuery($sql);
		
		$sql = "UPDATE setting 
				SET Value = '".$this->GetSetting(self::LAST_CHECK_TIME)."'
				WHERE name = '".self::LAST_CHECK_TIME."'";
		$result &= CDataManager::GetInstance()->ExercuseQuery($sql);
		
		return $result;
	}
	
	function GetSetting($name)
	{
		$i = 0;
		while($i < $this->count)
		{
			if($this->data[$i]["Name"] == $name)
			{
				return $this->data[$i]["Value"];
			}
			$i++;
		}
		return null;
	}
	
	function SetSetting($name, $value)
	{
		$i = 0;
		while($i < $this->count)
		{
			if($this->data[$i]["Name"] == $name)
			{
				$this->data[$i]["Value"] = $value;
				return;
			}
			$i++;
		}
	}
}
?>