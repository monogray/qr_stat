<?php
include_once 'model/bd_tables/table_entity.php';
class IssuePriority extends Table_Entity{
	public $table_name = 'issue_priority';
	static public $instance = null;
	
	public $id_order;
	public $id;
	public $name_ru;
	public $name_eng;
	public $description;
	
	function IssuePriority() {
		parent::Table_Entity();
	}
	
	static public function getInstance(){
		if(IssuePriority::$instance == null){
			IssuePriority::$instance = new IssuePriority();
			IssuePriority::$instance->getMainList();
		}
		return IssuePriority::$instance;
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
		$_instance = IssuePriority::getInstance();
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
		/*$_name = $_POST['name'];
		$_summary = $_POST['summary'];
		$_description = $_POST['description'];
		$_description_2 = $_POST['description_2'];
		$_menu = $_POST['menu'];
		
		$q = 'UPDATE '.$this->table_name.' SET
			name			= "'.$_name.'",
			summary			= "'.$_summary.'",
			description		= "'.$_description.'",
			description_2	= "'.$_description_2.'",
			menu			= "'.$_menu.'"
			WHERE id = '.$_id.' LIMIT 1;';
		
		$this->run_query($q);

		$this->propertiesUpdate($_id);		
		$this->setInfoMessage('Issue successfully updated');*/
	}
	
	public function drop($_id) {
		parent::drop($_id);
		$this->setInfoMessage('Issue successfully dropped');
	}
}