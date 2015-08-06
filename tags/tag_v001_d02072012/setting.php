<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Finance - Setting</title>
</head>
<body>

<p><a href="detail.php">Detail</a> | <a href="category.php">Manage Category</a></p>

<h3>Setting</h3>

<?php
require("config.php");
require("common.php");
require("data_setting.php");
require("utils.php");

//*********************
// FUNCTIONS
//*********************
function list_setting($config)
{
$initalMoney = get_setting($config, 'InitialMoney'); 
$lastCheckTime = get_setting($config, 'LastCheckTime'); 
?>
<form action="setting.php" method="post">
<input type="hidden" name="action_type" value="submit_edit"/>

<table border="0">
	<tr>
		<td>Initial money</td>
		<td>
			<input type="text" name="InitialMoney" 
				value="<?php echo $initalMoney['Value']; ?>"/>
		</td>
	</tr>
	<tr>
		<td>Last check time</td>
		<td>
			<?php showTimeBox("setting_lastchecktime", $lastCheckTime['Value']); ?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="Save" /></td>
	</tr>
</table>
</form>
<?php
}

function submit_edit_setting($config)
{
	$settings = array();
	
	$settings[0] = $_POST['InitialMoney'];
	
	$time_maked = mktime($_POST['setting_lastchecktime_hour'], 
					$_POST['setting_lastchecktime_minute'], 
					$_POST['setting_lastchecktime_second'], 
					$_POST['setting_lastchecktime_month'], 
					$_POST['setting_lastchecktime_day'], 
					$_POST['setting_lastchecktime_year']);	
	$settings[1] = date("Y-m-d H:i:s", $time_maked);
	
	return edit_setting($config, $settings);
}

//*********************
// MAIN PROCESS
//*********************
$action_type = "list";
if(isset($_REQUEST['action_type']))
	$action_type = $_REQUEST['action_type'];
	
switch($action_type)
{
case "list":
	list_setting($config);
	break;
	
case "submit_edit":
	$isSuccess = submit_edit_setting($config);
	if($isSuccess)
		echo "<p>The setting is saved</p>";
	else
		echo "<p>CAN NOT save setting</p>";
		
	list_setting($config);
	break;
	
}

?>

</body>
</html>