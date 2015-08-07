<?php
class CCategoryManager
{
	static $data;
	static $count;
	
	static function LoadData()
	{
		self::$data = 0;
		self::$count = 0;
		
		$sql = "SELECT * FROM category ORDER BY Name";
		$categories = CDataManager::ExercuseQuery($sql);
		
		// Fetch data
		while($row = mysql_fetch_array($categories))
		{
			if(!self::$data)
				self::$data = array();
				
			self::$data[self::$count] = new CCategory($row['Id'], 
											$row['Name'],
											$row['Receipt'],
											$row['Description']);
			
			self::$count++;
		}
	}
	
	static function GetAllCategories()
	{
		self::LoadData();
		return self::$data;
	}
	
	static function GetNumberOfCategory()
	{
		self::LoadData();
		return self::$count;
	}
	
	static function GetCategoryByIndex($index)
	{
		self::LoadData();
		return self::$data[$index];
	}
	
	static function GetCategoryById($id)
	{
		self::LoadData();
		$i = 0;
		while($i < self::$count)
		{
			$category = self::$data[$i];
			if($category->GetId() == $id)
				return $category;				
			$i++;
		}
		return null;
	}
	
	static function GetCategoryByName($name)
	{
		self::LoadData();
		$i = 0;
		while($i < self::$count)
		{
			$category = self::$data[$i];
			if($category->GetName() == $name)
				return $category;				
			$i++;
		}
		return null;
	}	
	
	static function AddCategory($category)
	{
		$does_exist = self::DoesCategoryExist($category);
		if($does_exist)
			return 0;
		
		$sql = "INSERT INTO category (Name,Receipt,Description) 
				VALUES ('".$category->GetName()."',
						".$category->IsReceipt().",
						'".$category->GetDescription()."')";
		return CDataManager::ExercuseQuery($sql);
	}
	
	static function UpdateCategory($category)
	{
		$sql = "UPDATE category 
				SET Name = '".$category->GetName()."', 
					Receipt = ".$category->IsReceipt().", 
					Description = '".$category->GetDescription()."'
				WHERE Id = ".$category->GetId();
		return CDataManager::ExercuseQuery($sql);
	}
	
	static function DeleteCategory($id)
	{
		$sql = "DELETE FROM category WHERE Id=$id";
		return CDataManager::ExercuseQuery($sql);
	}
	
	static function DoesCategoryExist($new_category)
	{
		self::LoadData();
		
		$does_exist = FALSE;
		for($i = 0; $i < self::$count; $i++)
		{
			if($new_category->GetName() == self::$data[$i]->GetName())
			{
				$does_exist = TRUE;
				break;
			}
		}		
		return $does_exist;
	}
	
	static function ClearAllCategories()
	{
		$sql = "DELETE FROM category";
		if(!CDataManager::ExercuseQuery($sql))
			return 0;
		
		$sql = "ALTER TABLE category AUTO_INCREMENT=1";
		return CDataManager::ExercuseQuery($sql);
	}
	
	static function CreateTable()
	{
		$sql = "CREATE TABLE category(
					Id int NOT NULL AUTO_INCREMENT,
					Name varchar(255) NOT NULL,
					Receipt int NOT NULL DEFAULT 0,
					Description varchar(1024),
					PRIMARY KEY (Id)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		return CDataManager::ExercuseQuery($sql);
	}
	
	static function DropTable()
	{
		$sql = "DROP TABLE IF EXISTS category";
		return CDataManager::ExercuseQuery($sql);
	}
}
?>