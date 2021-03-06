<?php
session_start();
date_default_timezone_set("Asia/Saigon");

require("config.php");
require("utils.php");

function __autoload($class_name) {
	include "classes/".$class_name . '.php';
}

include "interface/form/Form.php";
include "interface/form/EditCategoryForm.php";
include "interface/ICategory.php";

require 'header.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" type="text/css" href="style/style.css"/>
	<title>Finance - Category</title>
</head>
<body id="category">

<div id="wrap">
	
<?php
	show_header(); 
?>
	
	<div id="content">	
		<div id="setting">		
<?php
			if(isset($_GET['id']))
			{
				$result = show_edit_category_form($_GET['id']);
				if($result == 0)
				{
					echo "<p><b>Identifier was not found.</b></p>";
				}
			}
?>
		</div> <!-- End of setting -->
	</div> <!-- End of content -->
	
</div> <!-- End of wrap -->

</body>
</html>