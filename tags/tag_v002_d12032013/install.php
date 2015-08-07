<html>
<head>
	<title>Install</title>
</head>
<body>

<?php
date_default_timezone_set("Asia/Saigon");

require("config.php");
require("utils.php");

function __autoload($class_name) {
	include "classes/".$class_name . '.php';
}

//*********************
// DROP TABLE IF EXIST
//*********************
echo "<p>Droping existing table if exist...</p>";

// Drop Category table
echo "<p>Drop table Category if exist<br/>";
if(CCategoryManager::DropTable())
	echo "Table Category is droped</p>";
else
	echo "Error has occured while drop table Category</p>";

// Drop Detail table
echo "<p>Drop table Record if exist<br/>";
if(CRecordManager::DropTable())
	echo "Table Record is droped</p>";
else
	echo "Error has occured while drop table Record</p>";

// Drop Setting table
echo "<p>Drop table Setting if exist<br/>";
if(CSettingManager::DropTable())
	echo "Table Setting is droped</p>";
else
	echo "Error has occured while drop table Setting</p>";

//*********************
// CREATE TABLE
//*********************
echo "<p>Creating table...</p>";

echo "<p>";

// Create Category table
if(CCategoryManager::CreateTable())
	echo "Created <b>Category</b> table<br/>";
else
	echo "Error has occured while create table Category<br/>";

// Create Detail table
if(CRecordManager::CreateTable())
	echo "Created <b>Record</b> table<br/>";
else
	echo "Error has occured while create table Record<br/>";
	
// Create Setting table
if(CSettingManager::CreateTable())
	echo "Created <b>Setting</b> table<br/>";
else
	echo "Error has occured while create table Setting<br/>";

echo "</p>";
	
echo "<p>Finish.</p>";
?>

</body>
</html>