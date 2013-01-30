<?php
class DBConnect {
	// DB Settings
	public $db_server;
	public $db_user;
	public $db_pass;
	public $db_name;
	
	function DBConnect() {
		//error_reporting(0);		// Do not display Errors
		$this->db_server = Settings::$db_server;
		$this->db_user = Settings::$db_user;
		$this->db_pass = Settings::$db_pass;
		$this->db_name = Settings::$db_name;
	}
	
	function connectToDB() {
		session_start();
		if(!isset($_SESSION['message']))
			$_SESSION['message'] = '';
		//SET SESSION old_passwords = FALSE;
		//SET PASSWORD = PASSWORD('5e038c07');
		define('CLIENT_LONG_PASSWORD', 1);
		$db = mysql_connect($this->db_server, $this->db_user, $this->db_pass, false, CLIENT_LONG_PASSWORD);
		mysql_query('/*!40101 SET NAMES "cp1251" */');			/* Change error with encoding */	
		
		if( mysql_error() != '' )
			echo '<h1>Сайт находится на реконструкции</h1>';
		if( !$db ){
			exit;
		}
		
		$db_name = $this->db_name;
		mysql_select_db($db_name, $db);
	}
	
	function getUserData() {
		if(isset($_GET['logout']) && $_GET['logout'] == 'true'){
			$_SESSION['current_user_id'] = '';
		}
		
		if( isset($_SESSION['current_user_id']) && $_SESSION['current_user_id'] != '' ){
			$this->updateUserData();
		}else{
			if(isset($_POST['login']) && isset($_POST['pass'])){
				$_q = 'SELECT * FROM '.Settings::$db_table_prefix_name.'users WHERE login="'.$_POST['login'].'" AND pass="'.$_POST['pass'].'" LIMIT 1;';
				$result = mysql_query($_q);
				if($result){
					$row = mysql_fetch_array($result);
					$_SESSION['current_user_id'] = $row['id'];
					
					$this->updateUserData();
				}
			}
		}
	}
	
	function updateUserData() {
		if( isset($_SESSION['current_user_id']) && $_SESSION['current_user_id'] != '' ){
			require_once Settings::$path_to_user_class_file;
			$_user = new Users();
			$_user->getOneItem($_SESSION['current_user_id']);
			if($_user->len == 1){
				Settings::setCurrentUser($_user);
			}
		}else{
			$_SESSION['current_user_id'] = '';
		}
	}
}