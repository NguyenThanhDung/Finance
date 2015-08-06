<html>
<head>
	<title>Install</title>
</head>
<body>

<?php
require("common.php");

// get connection
$conn = get_connection($config);

//*********************
// DROP TABLE IF EXIST
//*********************
echo "Droping existing table if exist...<br/>";

// Drop Category table
$sql = "DROP TABLE IF EXISTS category";
if(mysql_query($sql, $conn) or die(mysql_error()))
	echo "Droped <b>Category</b><br/>";

// Drop Detail table
$sql = "DROP TABLE IF EXISTS detail";
if(mysql_query($sql, $conn) or die(mysql_error()))
	echo "Droped <b>Detail</b><br/>";

// Drop Setting table
$sql = "DROP TABLE IF EXISTS setting";
if(mysql_query($sql, $conn) or die(mysql_error()))
	echo "Droped <b>Setting</b><br/>";

//*********************
// CREATE TABLE
//*********************
echo "<br/>Creating table...<br/>";

// Create Category table
$sql = "CREATE TABLE category(
	Id int NOT NULL AUTO_INCREMENT,
	Name varchar(255) NOT NULL,
	Receipt int NOT NULL DEFAULT 0,
	Description varchar(1024),
	PRIMARY KEY (Id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
if(mysql_query($sql, $conn) or die(mysql_error()))
	echo "Created <b>Category</b> table<br/>";

// Create Detail table
$sql = "CREATE TABLE detail(
	Id int NOT NULL AUTO_INCREMENT,
	CategoryId int NOT NULL,
	Detail varchar(255),
	Time int(10),
	Amount int,
	Description varchar(1024),
	PRIMARY KEY (Id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
if(mysql_query($sql, $conn) or die(mysql_error()))
	echo "Created <b>Detail</b> table<br/>";
	
// Create Setting table
$sql = "CREATE TABLE setting(
	Name varchar(100) NOT NULL,
	Value varchar(255) NOT NULL,
	Description varchar(1024),
	PRIMARY KEY (Name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
if(mysql_query($sql, $conn) or die(mysql_error()))
	echo "Created <b>Setting</b> table<br/>";

$sql = "INSERT INTO setting (Name,Value,Description) 
		VALUES ('Version','0.0.2','Version of finance product')";
mysql_query($sql, $conn) or die(mysql_error());
$sql = "INSERT INTO setting (Name,Value,Description) 
		VALUES ('InitialMoney','0','The amount of money as the first time using the finance product')";
mysql_query($sql, $conn) or die(mysql_error());
$sql = "INSERT INTO setting (Name,Value,Description) 
		VALUES ('LastCheckTime','577269000','Last time check the account')";
mysql_query($sql, $conn) or die(mysql_error());
echo "Set default setting value<br/>";
	
echo "<p>Finish.</p>";
?>

</body>
</html>