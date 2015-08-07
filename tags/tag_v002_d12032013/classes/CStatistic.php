<?php
class CStatistic
{
	static function GetAvailableMoney()
	{
		$initial_money = CSettingManager::GetSetting(CSettingManager::INITIAL_MONEY);
		$total_payment = self::GetTotalPayment();
		$total_receipt = self::GetTotalReceipt();
		
		return $initial_money - $total_payment + $total_receipt;
	}
	
	static function GetTotalPayment()
	{
		$sql = "SELECT SUM(Amount) AS TotalPayment FROM detail
				WHERE CategoryId IN
					(SELECT Id FROM category WHERE Receipt = 0)";
		$result = CDataManager::ExercuseQuery($sql);
		$first_row = mysql_fetch_array($result);
		return $first_row['TotalPayment'];
	}
	
	static function GetTotalReceipt()
	{
		$sql = "SELECT SUM(Amount) AS TotalReceipt FROM detail
				WHERE CategoryId IN
					(SELECT Id FROM category WHERE Receipt = 1)";
		$result = CDataManager::ExercuseQuery($sql);
		$first_row = mysql_fetch_array($result);
		return $first_row['TotalReceipt'];
	}
	
	static function GetTotalMoney($filter)
	{
		$sql = "SELECT SUM(Amount) AS TotalMoney FROM detail ";
		$sql .= $filter->GetWhereClauseCommand();
		
		$result = CDataManager::ExercuseQuery($sql);
		$first_row = mysql_fetch_array($result);
		return $first_row['TotalMoney'];
	}
}
?>