<?php
require "common.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" type="text/css" href="style/style.css"/>
	<title>Finance - Adding Record</title>
</head>
<body id="detail">

<div id="wrap">
	
	<div id="content">
	
		<div id="processing">
		
			<?php
			$category_id = $_POST['categoryId'];
			$detail = $_POST['detail'];
			$time = mktime($_POST['time_hour'], 
							$_POST['time_minute'], 
							0,	// second 
							$_POST['date_month'],
							$_POST['date_day'], 
							$_POST['date_year']);
			
			$amount = $_POST['amount'];
			$unit = $_POST['unit'];
			if($unit == "1000_vnd")
				$amount *= 1000;
			$description = isset($_POST['description']) ? $_POST['description'] : "";
			
			$result = 0;
			if(isset($category_id) && isset($detail) && isset($time) && isset($amount))
			{
				$result = submit_add_record($category_id, $detail, $time, $amount, $description);
			}
			
			if($result)
			{
				echo "<p><b>Adding record...</b></p>";
			}
			else
			{
				echo "<p><b>An error occurs. Redirecting to detail page...</b></p>";
			}
			?>
		
			<img alt="loading" src="image/loading.gif" />
		</div>
		
		<!-- Redirect to category page -->
		<script type="text/javascript">
		setTimeout(function () {
		   window.location.href= 'detail.php';
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