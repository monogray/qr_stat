<?php
error_reporting(E_ALL|E_STRICT);
date_default_timezone_set('Europe/London');
set_include_path('.'
				 .PATH_SEPARATOR . '../../mono_framework/mono_lib/'
				 .PATH_SEPARATOR . '../'
				 .PATH_SEPARATOR . get_include_path());

require_once 'connect.php';
require_once 'app_processing.php';


$db_connect = new DBConnect();
$db_connect->connectToDB();

$app_proc = new AppProcessing();
$app_proc->process();