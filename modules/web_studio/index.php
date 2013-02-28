<?php
error_reporting(E_ALL|E_STRICT);
date_default_timezone_set('Europe/Kiev');
set_include_path('.' . PATH_SEPARATOR . '../mono_framework/mono_lib/'
				  .PATH_SEPARATOR. '../mono_mvc/'
				  .PATH_SEPARATOR.get_include_path());

require_once 'index_mvc.php';