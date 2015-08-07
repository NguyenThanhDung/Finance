<?php
function show_setting_form()
{
	$initialMoney = CSettingManager::GetSetting(CSettingManager::INITIAL_MONEY);
	$lastCheckTime = CSettingManager::GetSetting(CSettingManager::LAST_CHECK_TIME);
	
	$settingForm = new SettingForm($initialMoney, $lastCheckTime);
	$settingForm->Show();
}

function submit_save_setting($initial_money, $last_check_time)
{
	return CSettingManager::SaveSetting($initial_money, $last_check_time);
}