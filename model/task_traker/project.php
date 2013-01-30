<?php
include_once 'model/bd_tables/table_entity.php';
class Project extends Table_Entity{
	public $table_name = 'projects';
	static public $instance = null;
	
	public $id_order;
	public $id;
	public $name;
	public $description;
	public $author;
	public $coordinator;
	public $status;
	public $date;
	
	public $users_one_entity;
	public $author_entity;
	public $coordinator_entity;
	
	public $status_one_entity;
	public $status_entity;
	
	function Project() {
		parent::Table_Entity();
	}
	
	static public function getInstance(){
		if(self::$instance == null){
			self::$instance = new self();
			self::$instance->getMainList();
		}
		return self::$instance;
	}

	protected function setValuesByData($_data) {
		include_once 'users.php';
		$this->users_one_entity = Users::getInstance();
		
		include_once 'project_status.php';
		$this->status_one_entity = ProjectStatus::getInstance();
		
		$this->len = count($_data);
		for ($i = 0; $i < $this->len; $i++) {
			$this->id[$i]				= $_data[$i]['id'];
			$this->id_order[ $this->id[$i] ] = $i;
			$this->name[$i]				= $_data[$i]['name'];
			$this->description[$i]		= htmlspecialchars_decode($_data[$i]['description']);
			$this->author[$i]			= $_data[$i]['author'];
			$this->coordinator[$i]		= $_data[$i]['coordinator'];
			$this->status[$i]			= $_data[$i]['status'];
			$this->date[$i]				= $_data[$i]['date'];
			
			$this->author_entity[$i] = new Users();
			$this->author_entity[$i]->setValuesByInstanceAndIdLazy($this->author[$i]);
			
			$this->coordinator_entity[$i] = new Users();
			$this->coordinator_entity[$i]->setValuesByInstanceAndIdLazy($this->coordinator[$i]);
			
			$this->status_entity[$i] = new ProjectStatus();
			$this->status_entity[$i]->setValuesByInstanceAndIdLazy($this->status[$i]);
		}
	}
	
	public function setValuesByInstanceAndIdLazy($_id) {
		$_instance = Project::getInstance();
		if(isset( $_instance->id_order[$_id] )){
			$__id = $_instance->id_order[$_id];
		}else{
			$__id = 0;
		}
		$this->id[0]			= $_instance->id[$__id];
		$this->name[0]			= $_instance->name[$__id];
		$this->description[0]	= $_instance->description[$__id];
		$this->author[0]		= $_instance->author[$__id];
		$this->coordinator[0]	= $_instance->coordinator[$__id];
		$this->status[0]		= $_instance->status[$__id];
		$this->date[0]			= $_instance->date[$__id];
		$this->author_entity[0]	= $_instance->author_entity[$__id];
		$this->len = 1;
	}
	
	public function getIssueCountByProjectId($_project_id) {
		include_once 'issue.php';
		return Issue::getIssueCountByProjectId($_project_id);
	}
	
	public function getMainList() {
		// Get project by users permitions
		if(Settings::getCurrentUser()->user_rights[0]->edit_all_projects_id[0] == 'all'){
			$_q = 'SELECT * FROM '.$this->table_name.';';
		}else{
			$_q = 'SELECT * FROM '.$this->table_name.' WHERE id IN ('.Settings::getCurrentUser()->user_rights[0]->edit_all_projects_id[0].');';
		}
		$this->dat = $this->query_to_dat($_q);
		$this->setValuesByData($this->dat);
	}
	
	public function getOneItem($_id) {
		$this->dat = $this->select_One_by_id($_id);
		$this->setValuesByData($this->dat);
	}
	
	public function createNew() {
		$q ='INSERT INTO '.$this->table_name.'
				(id, name, author, date) VALUES(
					0,
					"New project",
					'.Settings::getCurrentUserId().',
					"'.date("Y-m-d H:i:s").'"
				)';
		$this->run_query($q);

		$this->setInfoMessage('Project successfully created');
		return mysql_insert_id();
	}
	
	public function updateItem($_id) {
		$_description = htmlspecialchars($_POST['description']);
		$_coordinator = $_POST['coordinator'];
		$_status = $_POST['status'];
		
		$q = 'UPDATE '.$this->table_name.' SET
			description		= "'.$_description.'",
			coordinator		= "'.$_coordinator.'",
			status			= "'.$_status.'"
			WHERE id = '.$_id.' LIMIT 1;';
		
		$this->run_query($q);
		$this->setInfoMessage('Project successfully updated');
	}
	
	public function drop($_id) {
		parent::drop($_id);
		$this->setInfoMessage('Issue successfully dropped');
	}
}