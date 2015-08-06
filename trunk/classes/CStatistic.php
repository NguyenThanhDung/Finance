<?php
class CStatistic
{
	static $instance;
	
	static function GetInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new CStatistic();
		}
		return self::$instance;
	}
	
	function CStatistic()
	{
	}
	
	function GetAvailableMoney()
	{
		$initial_money = CSettingManager::GetInstance()->GetSetting(CSettingManager::INITIAL_MONEY);
		$total_payment = $this->GetTotalPayment();
		$total_receipt = $this->GetTotalReceipt();
		
		return $initial_money - $total_payment + $total_receipt;
	}
	
	function GetTotalPayment()
	{
		$sql = "SELECT SUM(Amount) AS TotalPayment FROM detail
				WHERE CategoryId IN
					(SELECT Id FROM category WHERE Receipt = 0)";
		$result = CDataManager::GetInstance()->ExercuseQuery($sql);
		$first_row = mysql_fetch_array($result);
		return $first_row['TotalPayment'];
	}
	
	function GetTotalReceipt()
	{
		$sql = "SELECT SUM(Amount) AS TotalReceipt FROM detail
				WHERE CategoryId IN
					(SELECT Id FROM category WHERE Receipt = 1)";
		$result = CDataManager::GetInstance()->ExercuseQuery($sql);
		$first_row = mysql_fetch_array($result);
		return $first_row['TotalReceipt'];
	}
	
	function GetTotalMoney($filter)
	{
		$sql = "SELECT SUM(Amount) AS TotalMoney FROM detail ";
		$sql .= $filter->GetWhereClauseCommand();
		
		$result = CDataManager::GetInstance()->ExercuseQuery($sql);
		$first_row = mysql_fetch_array($result);
		return $first_row['TotalMoney'];
	}
}
?>