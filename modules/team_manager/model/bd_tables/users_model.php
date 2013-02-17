<?php
include_once 'model/bd_tables/table_entity.php';
class UsersModel extends Table_Entity{
	public $table_name = 'mono';
	private	$users;
	
	function UsersModel() {
	}
	
	protected function setValuesByData($_data) {
	}
	
	public function getMainList() {
	}
	
	public function getOneItem($_id) {
	}
	
	public function createNew() {
	}
	
	public function getUsersList() {
		include_once 'model/task_traker/users.php';
		$this->users = Users::getInstance();
	}
	
	public function getUsersById($_id) {
		include_once 'model/task_traker/users.php';
		$this->users = Users::getInstance();
		$this->users->getOneItem($_id);
	}
	
	
	
	// GETTERS
	public function getUsers() {
		return $this->users;
	}
}