<?php
require "common.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" type="text/css" href="style/style.css"/>
	<title>Finance - Deleting record</title>
</head>
<body id="detail">

<div id="wrap">
	
	<div id="content">
	
		<div id="processing">
		
			<?php
			$id = $_GET['id'];
			
			$result = 0;
			if(isset($id))
			{
				$result = submit_delete_record($id);
			}
			
			if($result)
			{
				echo "<p><b>Deleting record...</b></p>";
			}
			else
			{
				echo "<p><b>An error occurs. Redirecting to record page...</b></p>";
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