<?php
require "common.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" type="text/css" href="style/style.css"/>
	<title>Finance - Updating Setting</title>
</head>
<body id="record">

<div id="wrap">
	
	<div id="content">
	
		<div id="processing">
		
			<?php			
			$initial_money = $_POST['initial_money'];
			$last_check_time = mktime($_POST['last_check_time_hour'], 
							$_POST['last_check_time_minute'], 
							0,	// second 
							$_POST['last_check_time_month'],
							$_POST['last_check_time_day'], 
							$_POST['last_check_time_year']);
			
			$result = 0;
			if(isset($initial_money) &&isset($last_check_time))
			{
				$result = submit_save_setting($initial_money, $last_check_time);
			}
			
			if($result)
			{
				echo "<p><b>Saving Setting...</b></p>";
			}
			else
			{
				echo "<p><b>An error occurs. Redirecting to Setting page...</b></p>";
			}
			?>
		
			<img alt="loading" src="image/loading.gif" />
		</div>
		
		<!-- Redirect to category page -->
		<script type="text/javascript">
		setTimeout(function () {
		   window.location.href= 'setting.php';
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