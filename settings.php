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
	
	// Team Manager
	//public static $db_table_prefix_name = 'mono_';
	//public static $path_to_user_class_file = 'model/task_traker/users.php';
	//public static $path_to_attachments_dir = '../team_manager/';
	
	// QR State
	//public static $db_table_prefix_name = 'qr_manager_';
	//public static $path_to_user_class_file = 'model/bd_tables/users.php';
	//public static $path_to_attachments_dir = './attach/';
	
	// Web studio
	/*public static $db_table_prefix_name = 'webstudio_';
	public static $path_to_user_class_file = 'model/bd_tables/users.php';
	public static $path_to_attachments_dir = './modules/web_studio/public/attach/';
	public static $path_to_current_module = 'modules/web_studio/';				// Debug
	//public static $path_to_current_module = '../webstudio/modules/web_studio/';		// Prodaction
	*/
	// Web service. monoSaaS
	public static $db_table_prefix_name = 'monosaas_';
	public static $path_to_user_class_file = 'model/bd_tables/users.php';
	public static $path_to_attachments_dir = './modules/mono_saas/public/attach/';
	public static $path_to_current_module = 'modules/mono_saas/';						// Debug
	//public static $path_to_current_module = '../mono_saas/modules/mono_saas/';		// Prodaction
	
	private static $current_user;							// User entity. Contains an instance of current user
	
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