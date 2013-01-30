<?php
/**
 * @author Monogray
 * Define all apps settings
 */
class Settings {
	// DB Settings
	public static $db_server	= 'mysql302.1gb.ua';
	public static $db_user		= 'gbua_nvt_db';
	public static $db_pass		= '5e038c07';
	public static $db_name		= 'gbua_nvt_db';
	
	public static $db_table_prefix_name = 'mono_';
	public static $path_to_user_class_file = 'model/task_traker/users.php';
	public static $path_to_attachments_dir = '../team_manager/';
	//public static $db_table_prefix_name = 'qr_manager_';
	
	private static $current_user;
	
	public static function isUserLogined() {
		if(isset(self::$current_user) && self::$current_user->len == 1){
			return true;
		}else{
			return false;
		}
	}
	
	public static function setCurrentUser($_current_user) {
		self::$current_user = $_current_user;
	}
	
	public static function getCurrentUser() {
		return self::$current_user;
	}
	
	public static function getCurrentUserId() {
		return self::$current_user->id[0];
	}
}