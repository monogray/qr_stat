<?php
include_once 'model/bd_tables/table_entity.php';
class IssueHistory extends Table_Entity{
	public $table_name = 'issue_history';
	
	public $id_order;
	public $id;
	public $issue_id;
	public $category;
	public $old_value;
	public $new_value;
	public $author;
	public $date;
	
	public $category_entity;
	public $author_entity;
	
	function IssueHistory() {
		parent::Table_Entity();
	}

	protected function setValuesByData($_data) {
		include_once 'users.php';
		include_once 'issue_history_category.php';
		
		$this->len = count($_data);
		for ($i = 0; $i < $this->len; $i++) {
			$this->id[$i]				= $_data[$i]['id'];
			$this->id_order[ $this->id[$i] ] = $i;
			$this->issue_id[$i]			= $_data[$i]['issue_id'];
			$this->category[$i]			= $_data[$i]['category'];
			$this->old_value[$i]		= htmlspecialchars_decode($_data[$i]['old_value']);
			$this->new_value[$i]		= htmlspecialchars_decode($_data[$i]['new_value']);
			$this->author[$i]			= $_data[$i]['author'];
			$this->date[$i]				= $_data[$i]['date'];
			
			$this->author_entity[$i] = new Users();
			$this->author_entity[$i]->setValuesByInstanceAndIdLazy($this->author[$i]);
			
			$this->category_entity[$i] = new IssueHistoryCategory();
			$this->category_entity[$i]->setValuesByInstanceAndIdLazy($this->category[$i]);
		}
	}
	
	public function getMainList() {
		$this->dat = $this->select_All_default();
		$this->setValuesByData($this->dat);
	}
	
	public function getMainListByIssueId($_id) {
		$this->dat = $this->query_to_dat("SELECT * FROM $this->table_name WHERE issue_id=$_id ORDER BY date ASC;");
		$this->setValuesByData($this->dat);
	}
	
	public function getOneItem($_id) {
		$this->dat = $this->select_One_by_id($_id);
		$this->setValuesByData($this->dat);
	}
	
	public function addNew($_issue_id, $_category, $_old_value, $_new_value) {
		$_new_value = htmlspecialchars($_new_value);
		$_old_value = htmlspecialchars($_old_value);
		$q ='INSERT INTO '.$this->table_name.'
			(id, issue_id, category, old_value, new_value, author, date) VALUES(
				0,
				'.$_issue_id.',
				'.$_category.',
				"'.$_old_value.'",
				"'.$_new_value.'",
				'.Settings::getCurrentUser()->id[0].',
				"'.date("Y-m-d H:i:s").'"
			);';
		$this->run_query($q);
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