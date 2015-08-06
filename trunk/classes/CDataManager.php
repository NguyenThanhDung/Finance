<?php
class CDataManager
{
	static $instance = 0;
	var $connection;
	
	static function GetInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new CDataManager();
		}
		return self::$instance;
	}
	
	function CDataManager()
	{
		$this->GetConnection();
	}
	
	function GetConnection()
	{
		if(!$this->connection)
		{
			// open the connection
			$this->connection = mysql_connect(Config::SERVER_NAME, Config::USERNAME, Config::PASSWORD);
			if (!$this->connection)
			{
				die('Could not connect: ' . mysql_error());
			}
			// pick the database to use
			mysql_select_db(Config::DATABASE_NAME, $this->connection);
		}
		return $this->connection;
	}
	
	function ExercuseQuery($sql)
	{
		// execute the SQL statement
		DLOG("sql=$sql");
		$result = mysql_query($sql, $this->GetConnection()) or die(mysql_error());
		return $result;
	}
}
?>