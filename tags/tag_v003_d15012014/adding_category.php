<?php
session_start();
date_default_timezone_set("Asia/Saigon");

require("config.php");
require("utils.php");

function __autoload($class_name) {
	include "classes/".$class_name . '.php';
}

include "interface/ICategory.php";
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
	
	<div id="content">
	
		<div id="processing">
		
			<?php
			$name = $_POST['name'];
			$receipt = ($_POST['type'] == "receipt") ? 1 : 0;
			$description = isset($_POST['description']) ? $_POST['description'] : "";
			
			$result = 0;
			if(isset($name) && isset($receipt))
			{
				$result = submit_add_category($name, $receipt, $description);
			}
			
			if($result)
			{
				echo "<p><b>Adding category...</b></p>";
			}
			else
			{
				echo "<p><b>An error occurs. Redirecting to category page...</b></p>";
			}
			?>
		
			<img alt="loading" src="image/loading.gif" />
		</div>
		
		<!-- Redirect to category page -->
		<script type="text/javascript">
		setTimeout(function () {
		   window.location.href= 'category.php';
		},5000);
		</script>
		
	</div> <!-- End of content -->
	
	<div id="footer_buffer">
		<p>&nbsp;</p>
	</div> <!-- End of footer -->
	
	<!-- <div id="footer">
		<ul>
			<li style="float: left">Project Name Version 0.0.3</li>
			<li style="float: right">Copyright &#64 2013 TaZu Group. All Right Reserved.</li>
		</ul>
	</div> --> <!-- End of footer -->
	
</div> <!-- End of wrap -->

</body>
</html>