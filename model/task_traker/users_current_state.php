<?php
include_once 'model/bd_tables/table_entity.php';
class UserCurrentState extends Table_Entity{
	public $table_name = 'users_current_state';
	
	public $id_order;
	public $id;
	public $users_id;
	public $last_activity;
	
	public $user_entity;
	
	function UserCurrentState() {
		parent::Table_Entity();
	}

	protected function setValuesByData($_data) {
		$this->len = count($_data);
		include_once 'users.php';
		for ($i = 0; $i < $this->len; $i++) {
			$this->id_order[$i]			= $i;
			$this->id[$i]				= $_data[$i]['id'];
			$this->users_id[$i]			= $_data[$i]['users_id'];
			$this->last_activity[$i]	= $_data[$i]['last_activity'];
			
			$this->user_entity[$i] = new Users();
			$this->user_entity[$i]->setValuesByInstanceAndIdLazy($this->users_id[$i]);
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
	
	public function getOneItemByUserId($_id) {
		$this->dat = $this->query_to_dat("SELECT * FROM $this->table_name WHERE users_id=$_id LIMIT 1;");
		$this->setValuesByData($this->dat);
	}
	
	public function updateByUserId($_id) {
		$q = 'UPDATE '.$this->table_name.' SET
			last_activity = '.time().'
			WHERE users_id = '.$_id.' LIMIT 1;';
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