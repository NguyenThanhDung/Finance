<?php
class CCategoryManager
{
	static $instance;
	var $data;
	var $count;
	
	static function GetInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new CCategoryManager();
		}
		return self::$instance;
	}
	
	function CCategoryManager()
	{
		$this->data = 0;
		$this->count = 0;
		
		$this->LoadData();
	}
	
	function LoadData()
	{
		$sql = "SELECT * FROM category ORDER BY Name";
		$categories = CDataManager::GetInstance()->ExercuseQuery($sql);
		
		// Fetch data
		while($row = mysql_fetch_array($categories))
		{
			if(!$this->data)
				$this->data = array();
				
			$this->data[$this->count] = new CCategory($row['Id'], 
											$row['Name'],
											$row['Receipt'],
											$row['Description']);
			
			$this->count++;
		}
	}
	
	function GetAllCategories()
	{
		return $this->data;
	}
	
	function GetNumberOfCategory()
	{
		return $this->count;
	}
	
	function GetCategoryByIndex($index)
	{
		return $this->data[$index];
	}
	
	function GetCategoryById($id)
	{
		$i = 0;
		while($i < $this->count)
		{
			$category = $this->data[$i];
			if($category->GetId() == $id)
				return $category;				
			$i++;
		}
		return null;
	}
	
	function GetCategoryByName($name)
	{
		$i = 0;
		while($i < $this->count)
		{
			$category = $this->data[$i];
			if($category->GetName() == $name)
				return $category;				
			$i++;
		}
		return null;
	}	
	
	function AddCategory($category)
	{
		$sql = "INSERT INTO category (Name,Receipt,Description) 
				VALUES ('".$category->GetName()."',
						".$category->IsReceipt().",
						'".$category->GetDescription()."')";
		return CDataManager::GetInstance()->ExercuseQuery($sql);
	}
	
	function UpdateCategory($category)
	{
		$sql = "UPDATE category 
				SET Name = '".$category->GetName()."', 
					Receipt = ".$category->IsReceipt().", 
					Description = '".$category->GetDescription()."'
				WHERE Id = ".$category->GetId();
		return CDataManager::GetInstance()->ExercuseQuery($sql);
	}
	
	function DeleteCategory($id)
	{
		$sql = "DELETE FROM category WHERE Id=$id";
		return CDataManager::GetInstance()->ExercuseQuery($sql);
	}
	
	function ClearAllCategories()
	{
		$sql = "DELETE FROM category";
		if(!CDataManager::GetInstance()->ExercuseQuery($sql))
			return 0;
		
		$sql = "ALTER TABLE category AUTO_INCREMENT=1";
		return CDataManager::GetInstance()->ExercuseQuery($sql);
	}
}
?>