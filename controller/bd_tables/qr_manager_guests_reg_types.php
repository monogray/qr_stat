<?php 
include_once 'table_entity.php';
class QR_manager_guests_reg_types  extends Table_Entity{
	public $id;
	public $name;
	public $name_by_id;
	public $description;
	public $date;

	function QR_manager_guests_reg_types() {
		$this->table_name = 'qr_manager_guests_reg_types';
	}
	
	public function getFullList() {
		$this->dat = $this->select_All_default();
		$this->setValuesByData($this->dat);
	}
	
	public function getOneItem($_id) {
		$this->dat = $this->select_One_by_id($_id);
		$this->setValuesByData($this->dat);
	}
	
	public function setValuesByData($_data) {
		$this->len = count($_data);
		for ($i = 0; $i < $this->len; $i++) {
			$this->id[$i] = $_data[$i]['id'];
			$this->name[$i] = $_data[$i]['name'];
			$this->name_by_id[ $this->id[$i] ] = $_data[$i]['name'];
			$this->description[$i] = $_data[$i]['description'];
			$this->date[$i] = $_data[$i]['date'];
		}
	}
	
	public function dropOne($_id) {
		$this->drop($_id);
		
		$q ='DELETE FROM '.$this->table_names['events_activity'].' WHERE events_id = '.$_id.';';
		$this->run_query($q);
		
		$this->setInfoMessage('Event successfully deleted');
	}
	
	
	
	
	
	
	// Deprecated!!!
	public function updateItem($_id) {
		$_name = $_POST['name'];
		$_description = $_POST['description'];
	
		$q = 'UPDATE '.$this->guests_tab_name.' SET
			name = "'.$_name.'",
			description = "'.$_description.'"
			WHERE id = '.$_id.' LIMIT 1;';
	
		$this->run_query($q);
	}
}
?>