<?php
function show_setting_form()
{
	$initialMoney = CSettingManager::GetSetting(CSettingManager::INITIAL_MONEY);
	$lastCheckTime = CSettingManager::GetSetting(CSettingManager::LAST_CHECK_TIME);
	
	$settingForm = new SettingForm($initialMoney, $lastCheckTime);
	$settingForm->Show();
}
