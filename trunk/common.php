<?php
session_start();
date_default_timezone_set("Asia/Saigon");

require "config.php";
require "utils.php";

include "classes/CRecord.php";
include "classes/CCategory.php";
include "classes/CFilter.php";
include "classes/CStatistic.php";

include "classes/CDataManager.php";
include "classes/CRecordManager.php";
include "classes/CCategoryManager.php";
include "classes/CSettingManager.php";

include "interface/form/Form.php";
include "interface/form/AddRecordForm.php";
include "interface/form/EditRecordForm.php";
include "interface/form/AddCategoryForm.php";
include "interface/form/EditCategoryForm.php";
include "interface/form/SettingForm.php";

include "interface/IHeader.php";
include "interface/IDetail.php";
include "interface/ICategory.php";
include "interface/ISetting.php";
?>