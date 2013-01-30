<?php
include_once 'model/bd_tables/table_entity.php';
class UsersRights extends Table_Entity{
	public $table_name = 'users_rights';
	static public $instance = null;
	
	public $id_order;
	public $user_id_order;
	
	public $id;
	public $user_id;
	public $view_projects_id;
	public $edit_own_projects_id;
	public $edit_all_projects_id;
	public $edit_users;
	public $edit_projects;
	public $notification_high_priority_changes;
	public $notification_medium_priority_changes;
	public $notification_low_priority_changes;
	public $notification_coordinator_projects;
	
	function UsersRights() {
		parent::Table_Entity();
	}
	
	static public function getInstance(){
		if(UsersRights::$instance == null){
			UsersRights::$instance = new UsersRights();
			UsersRights::$instance->getMainList();
		}
		return UsersRights::$instance;
	}

	protected function setValuesByData($_data) {
		$this->len = count($_data);
		for ($i = 0; $i < $this->len; $i++) {
			$this->id[$i]						= $_data[$i]['id'];
			$this->id_order[ $this->id[$i] ]	= $i;
			$this->user_id[$i]					= $_data[$i]['user_id'];
			$this->user_id_order[ $this->user_id[$i] ] = $i;
			$this->view_projects_id[$i]			= $_data[$i]['view_projects_id'];
			$this->edit_own_projects_id[$i]		= $_data[$i]['edit_own_projects_id'];
			$this->edit_all_projects_id[$i]		= $_data[$i]['edit_all_projects_id'];
			$this->edit_users[$i]				= $_data[$i]['edit_users'];
			$this->edit_projects[$i]			= $_data[$i]['edit_projects'];
			
			$this->notification_high_priority_changes[$i]		= $_data[$i]['notification_high_priority_changes'];
			$this->notification_medium_priority_changes[$i]		= $_data[$i]['notification_medium_priority_changes'];
			$this->notification_low_priority_changes[$i]		= $_data[$i]['notification_low_priority_changes'];
			$this->notification_coordinator_projects[$i]		= $_data[$i]['notification_coordinator_projects'];
		}
	}
	
	public function setValuesByInstanceAndIdLazy($_id) {
		$_instance = UsersRights::getInstance();
		if(isset( $_instance->id_order[$_id] )){
			$__id = $_instance->id_order[$_id];
		}else{
			$__id = 0;
		}
		$this->id[0]					= $_instance->id[$__id];
		$this->user_id[0]				= $_instance->user_id[$__id];
		$this->view_projects_id[0]		= $_instance->view_projects_id[$__id];
		$this->edit_own_projects_id[0]	= $_instance->edit_own_projects_id[$__id];
		$this->edit_all_projects_id[0]	= $_instance->edit_all_projects_id[$__id];
		$this->edit_users[0]			= $_instance->edit_users[$__id];
		$this->edit_projects[0]			= $_instance->edit_projects[$__id];
		
		$this->notification_high_priority_changes[0]	= $_instance->notification_high_priority_changes[$__id];
		$this->notification_medium_priority_changes[0]	= $_instance->notification_medium_priority_changes[$__id];
		$this->notification_low_priority_changes[0]		= $_instance->notification_low_priority_changes[$__id];
		$this->notification_coordinator_projects[0]		= $_instance->notification_coordinator_projects[$__id];
		$this->len = 1;
	}
	
	public function setValuesByInstanceAndUserIdLazy($_id) {
		$_instance = UsersRights::getInstance();
		if(isset( $_instance->user_id_order[$_id] )){
			$__id = $_instance->user_id_order[$_id];
		}else{
			$__id = 0;
		}
		$this->id[0]					= $_instance->id[$__id];
		$this->user_id[0]				= $_instance->user_id[$__id];
		$this->view_projects_id[0]		= $_instance->view_projects_id[$__id];
		$this->edit_own_projects_id[0]	= $_instance->edit_own_projects_id[$__id];
		$this->edit_all_projects_id[0]	= $_instance->edit_all_projects_id[$__id];
		$this->edit_users[0]			= $_instance->edit_users[$__id];
		$this->edit_projects[0]			= $_instance->edit_projects[$__id];
	
		$this->notification_high_priority_changes[0]	= $_instance->notification_high_priority_changes[$__id];
		$this->notification_medium_priority_changes[0]	= $_instance->notification_medium_priority_changes[$__id];
		$this->notification_low_priority_changes[0]		= $_instance->notification_low_priority_changes[$__id];
		$this->notification_coordinator_projects[0]		= $_instance->notification_coordinator_projects[$__id];
		$this->len = 1;
	}
	
	public function getMainList() {
		$this->dat = $this->select_All_default();
		$this->setValuesByData($this->dat);
	}
	
	public function getOneItem($_id) {
		$this->dat = $this->select_One_by_id($_id);
		$this->setValuesByData($this->dat);
	}
	
	public function getOneItemByUserId($_user_id) {
		$_q = 'SELECT * FROM '.$this->table_name.' WHERE user_id='.$_user_id.' LIMIT 1;';
		$this->dat = $this->query_to_dat($_q);
		$this->setValuesByData($this->dat);
	}
	
	public function createNew() {
	}
	
	public function updateItem($_id) {
	}
	
	public function updateByIserIdAndNumber($_user_id, $_number) {
		$_project_id = $_POST['project'];
		$_status = $_POST['status'];
		
		$q = 'UPDATE '.$this->table_name.' SET
			project_id			= '.$_project_id.',
			status			= '.$_status.'
			WHERE user_id = '.$_user_id.' AND filter_number = '.$_number.' LIMIT 1;';
		$this->run_query($q);
	}
	
	public function drop($_id) {
		parent::drop($_id);
		$this->setInfoMessage('Issue successfully dropped');
	}
}