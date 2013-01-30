<?php
include_once 'model/bd_tables/table_entity.php';
class IssueHistoryCategory extends Table_Entity{
	public $table_name = 'issue_history_category';
	
	static public $instance = null;
	
	public $id_order;
	public $id;
	public $name_ru;
	public $name_eng;
	public $description;
	
	function IssueHistoryCategory() {
		parent::Table_Entity();
	}

	static public function getInstance(){
		if(IssueHistoryCategory::$instance == null){
			IssueHistoryCategory::$instance = new IssueHistoryCategory();
			IssueHistoryCategory::$instance->getMainList();
		}
		return IssueHistoryCategory::$instance;
	}
	
	static public function updateInstance(){
		IssueHistoryCategory::$instance = null;
		IssueHistoryCategory::getInstance();
	}
	
	protected function setValuesByData($_data) {
		$this->len = count($_data);
		for ($i = 0; $i < $this->len; $i++) {
			$this->id[$i]				= $_data[$i]['id'];
			$this->id_order[ $this->id[$i] ] = $i;
			$this->name_ru[$i]			= $_data[$i]['name_ru'];
			$this->name_eng[$i]			= $_data[$i]['name_eng'];
			$this->description[$i]		= $_data[$i]['description'];
		}
	}
	
	public function setValuesByInstanceAndIdLazy($_id) {
		$_instance = IssueHistoryCategory::getInstance();
		if(isset( $_instance->id_order[$_id] )){
			$__id = $_instance->id_order[$_id];
		}else{
			$__id = 0;
		}
		$this->id[0]			= $_instance->id[$__id];
		$this->name_ru[0]		= $_instance->name_ru[$__id];
		$this->name_eng[0]		= $_instance->name_eng[$__id];
		$this->description[0]	= $_instance->description[$__id];
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
	
	public function addNew($_issue_id, $_category, $_old_value, $_new_value) {
	}
	
	public function createNew() {
	}
	
	public function updateItem($_id) {
	}
	
	public function drop($_id) {
		parent::drop($_id);
		$this->setInfoMessage('Category successfully dropped');
	}
}