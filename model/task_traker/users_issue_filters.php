<?php
include_once 'model/bd_tables/table_entity.php';
class UsersIssueFilter extends Table_Entity{
	public $table_name = 'users_issue_filters';
	
	public $id_order;
	public $id;
	public $user_id;
	public $filter_number;
	public $project_id;
	public $assigned_to;
	public $author;
	public $issue_type;
	public $status;
	public $priority;
	
	function UsersIssueFilter() {
		parent::Table_Entity();
	}

	protected function setValuesByData($_data) {
		$this->len = count($_data);
		for ($i = 0; $i < $this->len; $i++) {
			$this->id_order[$i]			= $i;
			$this->id[$i]				= $_data[$i]['id'];
			$this->user_id[$i]			= $_data[$i]['user_id'];
			$this->filter_number[$i]	= $_data[$i]['filter_number'];
			$this->project_id[$i]		= $_data[$i]['project_id'];
			$this->assigned_to[$i]		= $_data[$i]['assigned_to'];
			$this->author[$i]			= $_data[$i]['author'];
			$this->issue_type[$i]		= $_data[$i]['issue_type'];
			$this->status[$i]			= $_data[$i]['status'];
			$this->priority[$i]			= $_data[$i]['priority'];
		}
	}
	
	public function getMainList() {
		$this->dat = $this->select_All_default();
		$this->setValuesByData($this->dat);
	}
	
	public function getOneItem($_id) {
		$this->dat = $this->select_One_by_id($_id);
		$this->setValuesByData($this->dat);
	}
	
	public function getOneItemByIserIdAndNumber($_user_id, $_number) {
		$_q = 'SELECT * FROM '.$this->table_name.' WHERE user_id='.$_user_id.' AND filter_number='.$_number.' LIMIT 1;';
		$this->dat = $this->query_to_dat($_q);
		$this->setValuesByData($this->dat);
	}
	
	public function createNew() {
	}
	
	public function updateItem($_id) {
	}
	
	public function updateByIserIdAndNumber($_user_id, $_number) {
		$_project_id	= $_POST['project'];
		$_status		= $_POST['status'];
		$_assigned_to	= $_POST['assigned_to'];
		$_author		= $_POST['author'];
		
		$q = 'UPDATE '.$this->table_name.' SET
			project_id		= '.$_project_id.',
			status			= '.$_status.',
			assigned_to		= '.$_assigned_to.',
			author			= '.$_author.'
			WHERE user_id = '.$_user_id.' AND filter_number = '.$_number.' LIMIT 1;';
		$this->run_query($q);
	}
	
	public function drop($_id) {
		parent::drop($_id);
		$this->setInfoMessage('Issue successfully dropped');
	}
}