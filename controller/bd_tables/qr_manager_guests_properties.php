<?php 
include_once 'table_entity.php';
class QR_manager_guests_properties extends Table_Entity{
	public $id;
	public $guests_id;
	public $events_activity;
	public $date;
	
	function QR_manager_guests_properties() {
		$this->table_name = 'qr_manager_guests_properties';
	}
	
	public function getFullList() {
		$this->dat = $this->select_All_default();
		$this->setValuesByData($this->dat);
	}
	
	public function getOneItem($_id) {
		$this->dat = $this->select_One_by_id($_id);
		$this->setValuesByData($this->dat);
	}
	
	public function getOneItem_by_guest_id($_guest_id) {
		$this->dat = $this->query_to_dat("SELECT * FROM $this->table_name WHERE guests_id = $_guest_id LIMIT 1;");
		if(isset($this->dat))
			$this->setValuesByData($this->dat);
	}
	
	public function setValuesByData($_data) {
		$this->len = count($_data);
		for ($i = 0; $i < $this->len; $i++) {
			$this->id[$i]				= $_data[$i]['id'];
			$this->guests_id[$i]		= $_data[$i]['guests_id'];
			$this->events_activity[$i]	= $_data[$i]['events_activity'];
			$this->date[$i]				= $_data[$i]['date'];
		}
	}
	
	public function updateItem_eventsActivity($_id, $_events_activity) {
		if( !$this->check_IfEventsActivityExist_byGuestsId($_id) ){
			$q ='INSERT INTO '.$this->table_name.'
				(id, guests_id, events_activity) VALUES(
				0,
				'.$_id.',
				"'.$_events_activity.'"
			)';
		}else{
			$q = 'UPDATE '.$this->table_name.' SET
				events_activity = "'.$_events_activity.'"
				WHERE guests_id = '.$_id.' LIMIT 1;';
		}
		$this->run_query($q);
	}
	
	function check_IfEventsActivityExist_byGuestsId($_guests_id) {
		$result = mysql_query("SELECT COUNT(*) FROM $this->table_name WHERE guests_id = '$_guests_id';");
		$row = mysql_fetch_array($result);
		if($row[0] > 0)
			return true;
		else
			return false;
	}
}
?>