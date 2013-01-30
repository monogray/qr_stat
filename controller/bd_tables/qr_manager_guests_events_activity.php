<?php 
include_once 'table_entity.php';
class QR_manager_guests_events_activity extends Table_Entity{
	public $id;
	public $guests_id;
	public $events_id;
	public $activity;
	public $activity_date;
	
	function QR_manager_guests_events_activity() {
		$this->table_name = 'qr_manager_guests_events_activity';
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
			$this->id[$i]				= $_data[$i]['id'];
			$this->guests_id[$i]		= $_data[$i]['guests_id'];
			$this->events_id[$i]		= $_data[$i]['events_id'];
			$this->activity[$i]			= $_data[$i]['activity'];
			$this->activity_date[$i]	= $_data[$i]['activity_date'];
		}
	}
	
	public function updateActivityByGuestsId($_guests_id, $_events_id, $_value) {
		if($this->check_IfEventsActivityExist_byGuestsId($_guests_id, $_events_id)){
			$_current_val = $this->getOneActivityValue_ByGuestsIdAndEventsId($_guests_id, $_events_id);
			if($_value != $_current_val){
				$q = 'UPDATE '.$this->table_name.' SET
						activity = "'.$_value.'",
						activity_date = "'.date("Y-m-d H:i:s").'"
						WHERE guests_id = '.$_guests_id.' AND events_id = '.$_events_id.' LIMIT 1;';
				$this->run_query($q);
			}
		}else{
			$q ='INSERT INTO '.$this->table_name.'
				(id, guests_id, events_id, activity, activity_date) VALUES(
					0,
					"'.$_guests_id.'",
					"'.$_events_id.'",	
					"'.$_value.'",
					"'.date("Y-m-d H:i:s").'"
				)';
			$this->run_query($q);
		}
	}
	
	function check_IfEventsActivityExist_byGuestsId($_guests_id, $_events_id) {
		$result = mysql_query("SELECT COUNT(*) FROM $this->table_name WHERE guests_id='$_guests_id' AND events_id='$_events_id';");
		$row = mysql_fetch_array($result);
		if($row[0] > 0)
			return true;
		else
			return false;
	}
	
	function getActivitiesArrByGuestId($_guests_id) {
		return $this->query_to_dat("SELECT activity FROM $this->table_name WHERE guests_id='$_guests_id';");
	}
	
	function getOneActivityValue_ByGuestsIdAndEventsId($_guests_id, $_events_id) {
		$result = mysql_query("SELECT activity FROM $this->table_name WHERE guests_id='$_guests_id' AND events_id='$_events_id';");
		$row = mysql_fetch_array($result);
		return $row['activity'];
	}
	
}