<?php
require "common.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" type="text/css" href="style/style.css"/>
	<title>Finance - Detail</title>
</head>
<body id="detail">

<div id="wrap">
	
<?php
	show_header(); 
?>
	
	<div id="content">
		
<?php
		show_records(); 
?>
		
		<div class="box">
			<div class="box_top"></div>
			<div class="box_center">
<?php
				show_add_record_form();
?>
			</div>
			<div class="box_bottom"></div>
		</div> <!-- End of box -->
		
	</div> <!-- End of content -->
	
</div> <!-- End of wrap -->

</body>
</html>