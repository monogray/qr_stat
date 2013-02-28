<?php
// Need to comment block below if production
error_reporting(E_ALL|E_STRICT);
date_default_timezone_set('Europe/Kiev');
set_include_path('.'.PATH_SEPARATOR . '../mono_framework/mono_lib/'
				  .PATH_SEPARATOR.get_include_path());

require_once 'settings.php';
require_once 'controller/main_controller.php';
require_once 'connect.php';

$db_connect = new DBConnect();
$db_connect->connectToDB();
$db_connect->getUserData();

$main_controller = new MainController($db_connect);
$main_controller->process();