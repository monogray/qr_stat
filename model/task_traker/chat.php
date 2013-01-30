<?php
include_once 'model/bd_tables/table_entity.php';
class Chat extends Table_Entity{
	public $table_name = 'chat';
	
	static public $instance = null;
	
	public $id_order;
	public $id;
	public $name;
	public $author;
	public $project;
	
	public $author_entity;
	public $project_entity;
	
	function Chat() {
		parent::Table_Entity();
	}
	
	static public function getInstance(){
		if(Chat::$instance == null){
			Chat::$instance = new Chat();
			Chat::$instance->getMainListByUserRights();
		}
		return Chat::$instance;
	}
	
	static public function updateInstance(){
		Chat::$instance = null;
		Chat::getInstance();
	}

	protected function setValuesByData($_data) {
		include_once 'users.php';
		include_once 'project.php';
		$this->len = count($_data);
		for ($i = 0; $i < $this->len; $i++) {
			$this->id[$i]			= $_data[$i]['id'];
			$this->id_order[ $this->id[$i] ] = $i;
			$this->name[$i]			= $_data[$i]['name'];
			$this->author[$i]		= $_data[$i]['author'];
			$this->project[$i]		= $_data[$i]['project'];
			
			$this->author_entity[$i] = new Users();
			$this->author_entity[$i]->setValuesByInstanceAndIdLazy($this->author[$i]);
			
			$this->project_entity[$i] = new Project();
			$this->project_entity[$i]->setValuesByInstanceAndIdLazy($this->project[$i]);
		}
	}
	
	public function getMainList() {
		$this->dat = $this->select_All_default();
		$this->setValuesByData($this->dat);
	}
	
	public function getMainListByUserRights() {
		// Get project by users permitions
		if(Settings::getCurrentUser()->user_rights[0]->edit_all_projects_id[0] == 'all'){
			$_proj_q = 'SELECT id FROM mono_projects';
		}else{
			$_proj_q = 'SELECT id FROM mono_projects WHERE id IN ('.Settings::getCurrentUser()->user_rights[0]->edit_all_projects_id[0].')';
		}
	
		$this->dat = $this->query_to_dat("SELECT * FROM $this->table_name WHERE project IN ($_proj_q);");
		$this->setValuesByData($this->dat);
	}
	
	public function getOneItem($_id) {
		$this->dat = $this->select_One_by_id($_id);
		$this->setValuesByData($this->dat);
	}
	
	public function createNew() {
		/*$q ='INSERT INTO '.$this->table_name.'
				(id, name, lang, date) VALUES(
					0,
					"New issue",
					1,
					"'.date("Y-m-d H:i:s").'"
				)';
		$this->run_query($q);

		$this->setInfoMessage('Issue successfully created');
		return mysql_insert_id();*/
	}
	
	public function updateItem($_id) {
	}
	
	public function drop($_id) {
		parent::drop($_id);
		$this->setInfoMessage('Issue successfully dropped');
	}
}