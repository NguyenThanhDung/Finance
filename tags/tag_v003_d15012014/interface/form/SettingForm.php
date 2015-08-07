<?php
class SettingForm extends Form
{
	var $initialMoney;
	var $lastCheckTime;
	
	function SettingForm($initialMoney, $lastCheckTime)
	{
		parent::Form("image/edit_btn.png", "Setting", "editting_setting.php", "Save");
		$this->initialMoney = $initialMoney;
		$this->lastCheckTime = $lastCheckTime;
	}
	
	function Show()
	{
		parent::BeginShow();
		$this->ShowInputBox("initial_money", "Initial Money", $this->initialMoney);
		$this->ShowDateAndTimeInput("last_check_time", "Last Check Time", $this->lastCheckTime);
		parent::EndShow();
	}
}
?>