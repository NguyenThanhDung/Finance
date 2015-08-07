<?php
class CDataManager
{
	static $connection;
	
	static function GetConnection()
	{
		if(!self::$connection)
		{
			// open the connection
			self::$connection = mysql_connect(Config::SERVER_NAME, Config::USERNAME, Config::PASSWORD);
			if (!self::$connection)
			{
				die('Could not connect: ' . mysql_error());
			}
			// pick the database to use
			mysql_select_db(Config::DATABASE_NAME, self::$connection);
		}
		return self::$connection;
	}
	
	static function ExercuseQuery($sql)
	{
		// execute the SQL statement
		DLOG("sql=$sql");
		$result = mysql_query($sql, self::GetConnection()) or die(mysql_error());
		return $result;
	}
}
?>