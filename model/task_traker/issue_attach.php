<?php
include_once 'model/bd_tables/table_entity.php';
class Attach extends Table_Entity{
	public $table_name = 'issue_attach';
	static public $instance = null;
	
	public $id_order;
	public $id;
	public $issue_id;
	public $path;
	public $author;
	public $date;
	
	public $author_entity;
	
	function Attach() {
		parent::Table_Entity();
	}

	static public function getInstance(){
		if(Attach::$instance == null){
			Attach::$instance = new Attach();
			Attach::$instance->getMainList();
		}
		return Attach::$instance;
	}
	
	static public function updateInstance(){
		Attach::$instance = null;
		Attach::getInstance();
	}
	
	protected function setValuesByData($_data) {
		$this->len = count($_data);
		for ($i = 0; $i < $this->len; $i++) {
			$this->id[$i]			= $_data[$i]['id'];
			$this->id_order[ $this->id[$i] ] = $i;
			$this->issue_id[$i]		= $_data[$i]['issue_id'];
			$this->path[$i]			= $_data[$i]['path'];
			$this->author[$i]		= $_data[$i]['author'];
			$this->date[$i]			= $_data[$i]['date'];

			$this->author_entity[$i] = new Users();
			$this->author_entity[$i]->setValuesByInstanceAndIdLazy($this->author[$i]);
		}
	}
	
	public function setValuesByInstanceAndIdLazy($_id) {
		$_instance = Attach::getInstance();
		if(isset( $_instance->id_order[$_id] )){
			$__id = $_instance->id_order[$_id];
		}else{
			$__id = 0;
		}
		$this->id[0]			= $_instance->id[$__id];
		$this->issue_id[0]		= $_instance->issue_id[$__id];
		$this->path[0]			= $_instance->path[$__id];
		$this->author[0]		= $_instance->author[$__id];
		$this->date[0]			= $_instance->date[$__id];
		$this->author_entity[0]	= $_instance->author_entity[$__id];
		
		$this->len = 1;
	}
	
	public function setValuesByInstanceAndIssueIdLazy($_id) {
		$_instance = Attach::getInstance();
		$j = 0;
		for ($i = 0; $i < $_instance->len; $i++) {
			if($_instance->issue_id[$i] == $_id){
				$__id = $i;
				$this->id[$j]				= $_instance->id[$__id];
				$this->issue_id[$j]			= $_instance->issue_id[$__id];
				$this->path[$j]				= $_instance->path[$__id];
				$this->author[$j]			= $_instance->author[$__id];
				$this->date[$j]				= $_instance->date[$__id];
				$this->author_entity[$j]	= $_instance->author_entity[$__id];
				$j++;
			}
		}
		$this->len = count($this->id);
	}
	
	public function getMainList() {
		$this->dat = $this->select_All_default();
		$this->setValuesByData($this->dat);
	}
	
	public function getMainListByIssueId($_issue_id) {
		$this->dat = $this->query_to_dat("SELECT * FROM $this->table_name WHERE issue_id = $_issue_id;");
		$this->setValuesByData($this->dat);
	}
	
	public function getOneItem($_id) {
		$this->dat = $this->select_One_by_id($_id);
		$this->setValuesByData($this->dat);
	}
	
	public function createNew() {
	}
	
	public function createNewByPath($_issue_id, $_path) {
		$q ='INSERT INTO '.$this->table_name.'
		 (id, issue_id, path, author, date) VALUES(
	 		0,
	 		"'.$_issue_id.'",
	 		"'.$_path.'",
	 		'.Settings::getCurrentUser()->id[0].',
	 		"'.date("Y-m-d H:i:s").'"
		 )';
		$this->run_query($q);
	
		$this->setInfoMessage('Attach successfully created');
		return mysql_insert_id();
	}
	
	public function updateItem($_id) {
	}
	
	public function drop($_id) {
		$_att = new Attach();
		$_att->getOneItem($_id);
		unlink(Settings::$path_to_attachments_dir.$_att->path[0]);
		
		// History
		include_once 'issue_history.php';
		$_history = new IssueHistory();
		$_history->addNew($_GET['id'], 7, $_att->path[0], '');
		
		parent::drop($_id);
		$this->setInfoMessage('Attach successfully dropped');
	}
	
	public function getListByIssueId($_id) {
		$this->dat = $this->query_to_dat("SELECT * FROM $this->table_name WHERE issue_id=$_id ORDER BY id ASC;");
		$this->setValuesByData($this->dat);
	}
}