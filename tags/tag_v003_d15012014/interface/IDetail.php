<?php
function show_records()
{
	$category_ids 	= isset($_SESSION['filters']['categories']) ? $_SESSION['filters']['categories'] 	: null;
	$detail 		= isset($_SESSION['filters']['detail']) 	? $_SESSION['filters']['detail'] 		: null;
	$from_date 		= isset($_SESSION['filters']['from_date']) 	? $_SESSION['filters']['from_date']		: null;
	$to_date 		= isset($_SESSION['filters']['to_date']) 	? $_SESSION['filters']['to_date'] 		: null;
	$from_amount	= isset($_SESSION['filters']['from_amount'])? $_SESSION['filters']['from_amount'] 	: null;
	$to_amount 		= isset($_SESSION['filters']['to_amount']) 	? $_SESSION['filters']['to_amount'] 	: null;
	$description	= isset($_SESSION['filters']['description'])? $_SESSION['filters']['description']	: null;
	
	$filter = new CFilter(0, $category_ids, $detail, $from_date, $to_date, $from_amount, $to_amount, $description);
	CRecordManager::SetFilter($filter);
	
	$records = CRecordManager::GetRecords();
	if($records)
	{
?>
<div class="table">
	<table class="list">
		<tr>
			<th>Category</th>
			<th>Detail</th>
			<th>Time</th>
			<th>Amount</th>
			<!-- <th>Description</th> -->
			<th>Edit</th>
			<th>Delete</th>
		</tr>
		
<?php		
		for($i = 0; $i < count($records); $i++)
		{
			$record = $records[$i];
			
			if($i % 2 == 0)
				echo "<tr class='alt'>";
			else
				echo "<tr>";
			echo "	<td>".$record->GetCategory()->GetName()."</td>";
			echo "	<td>".$record->GetDetail()."&nbsp;</td>";
			echo "	<td>".date("Y-m-d H:i",$record->GetTime())."</td>";
			echo "	<td class='number_cell'>".$record->GetAmount()."</td>";
			echo "	<!-- <td>".$record->GetDescription()."&nbsp;</td>-->";
			echo "	<td class='image_cell'><a href='edit_record.php?id=".$record->GetId()."'><img src='image/edit_btn.png' /></a></td>";
			echo "	<td class='image_cell'><a href='deleting_record.php?id=".$record->GetId()."'><img src='image/delete_btn.png' /></a></td>";
			echo "<tr/>";
		} 
?>
	</table>
</div> <!-- End of table -->
<?php
	}
	else
	{
		echo "There is no any record";
	}
}


function show_add_record_form()
{
	$categories = CCategoryManager::GetAllCategories();
	$addRecordForm = new AddRecordForm("image/add_btn.png", "Add New Record", "adding_record.php", "Add", $categories);
	$addRecordForm->Show();
}

function submit_add_record($category_id, $detail, $time, $amount, $description)
{
	$record = new CRecord(0, $category_id, $detail, $time, $amount, $description);
	return CRecordManager::AddRecord($record);
}

function show_edit_record_form($id)
{
	$categories = CCategoryManager::GetAllCategories();
	$record = CRecordManager::GetRecordById($id);
	if($record)
	{
		$editRecordForm = new EditRecordForm("image/edit_btn.png", "Edit Record", "editting_record.php", "Save", $categories, $record);
		$editRecordForm->Show();
		return 1;
	}
	else
	{
		return 0;
	}
}

function submit_edit_record($id, $category_id, $detail, $time, $amount, $description)
{
	$record = new CRecord($id, $category_id, $detail, $time, $amount, $description);
	return CRecordManager::UpdateRecord($record);
}

function submit_delete_record($id)
{
	return CRecordManager::DeleteRecord($id);
}