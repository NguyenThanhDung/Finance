<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Finance - Setting</title>
</head>
<body>

<p><a href="detail.php">Detail</a> | <a href="category.php">Manage Category</a></p>

<h3>Setting</h3>

<?php
date_default_timezone_set("Asia/Saigon");

require("config.php");
require("utils.php");

function __autoload($class_name) 
{
    include "classes/".$class_name.'.php';
}

//*********************
// FUNCTIONS
//*********************
function list_setting()
{
$initalMoney = CSettingManager::GetSetting(CSettingManager::INITIAL_MONEY);
$lastCheckTime = CSettingManager::GetSetting(CSettingManager::LAST_CHECK_TIME);
?>
<form action="setting.php" method="post">
<input type="hidden" name="action_type" value="submit_edit"/>

<table border="0">
	<tr>
		<td>Initial money</td>
		<td>
			<input type="text" name="InitialMoney" 
				value="<?php echo $initalMoney; ?>"/>
		</td>
	</tr>
	<tr>
		<td>Last check time</td>
		<td>
			<?php showTimeBox("setting_lastchecktime", $lastCheckTime); ?>
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

function submit_edit_setting()
{
	DLOG("submit_edit_setting()");
	$initial_money = $_POST['InitialMoney'];
	DLOG("initial_money=$initial_money");
	
	$last_check_time = mktime($_POST['setting_lastchecktime_hour'], 
						$_POST['setting_lastchecktime_minute'], 
						$_POST['setting_lastchecktime_second'], 
						$_POST['setting_lastchecktime_month'], 
						$_POST['setting_lastchecktime_day'], 
						$_POST['setting_lastchecktime_year']);
	DLOG("last_check_time=$last_check_time");
	
	return CSettingManager::SaveSetting($initial_money, $last_check_time);
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
	list_setting();
	break;
	
case "submit_edit":
	$isSuccess = submit_edit_setting();
	if($isSuccess)
		echo "<p>The setting is saved</p>";
	else
		echo "<p>CAN NOT save setting</p>";
		
	list_setting();
	break;
	
}

?>

</body>
</html>