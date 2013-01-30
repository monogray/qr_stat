<?php
include_once 'model/bd_tables/table_entity.php';
class IssueProgress extends Table_Entity{
	public $table_name = 'issue_progress';
	static public $instance = null;
	
	public $id_order;
	public $id;
	public $percents;
	public $description;
	
	function IssueProgress() {
		parent::Table_Entity();
	}
	
	static public function getInstance(){
		if(IssueProgress::$instance == null){
			IssueProgress::$instance = new IssueProgress();
			IssueProgress::$instance->getMainList();
		}
		return IssueProgress::$instance;
	}

	protected function setValuesByData($_data) {
		$this->len = count($_data);
		for ($i = 0; $i < $this->len; $i++) {
			$this->id[$i]				= $_data[$i]['id'];
			$this->id_order[ $this->id[$i] ] = $i;
			$this->percents[$i]			= $_data[$i]['percents'];
			$this->description[$i]		= $_data[$i]['description'];
		}
	}
	
	public function setValuesByInstanceAndIdLazy($_id) {
		$_instance = IssueProgress::getInstance();
		if(isset( $_instance->id_order[$_id] )){
			$__id = $_instance->id_order[$_id];
		}else{
			$__id = 0;
		}
		$this->id[0]				= $_instance->id[$__id];
		$this->percents[0]			= $_instance->percents[$__id];
		$this->description[0]		= $_instance->description[$__id];
	}
	
	public function getMainList() {
		$this->dat = $this->select_All_default();
		$this->setValuesByData($this->dat);
	}
	
	public function getOneItem($_id) {
		$this->dat = $this->select_One_by_id($_id);
		$this->setValuesByData($this->dat);
	}
	
	public function createNew() {
	}
	
	public function updateItem($_id) {
	}
	
	public function drop($_id) {
		parent::drop($_id);
		$this->setInfoMessage('Issue successfully dropped');
	}
}