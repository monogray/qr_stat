<?php 
class Admin_GuestsView {
	public $id;
	public $name;
	public $surname;
	public $patronymic;
	public $groups;
	public $date;
	
	public $events_activity_str;
	public $events_activity_id = Array();
	public $events_activity_val = Array();
	public $events_activity_len;

	function Admin_GuestsView() {
	}
	
	public function setAllValuesByData($_data) {
		$this->setValuesByData($_data);
		$this->setActivityByData($_data);
	}
	
	public function setValuesByData($_data) {
		$this->id = $_data[0]['id'];
		$this->name = $_data[0]['name'];
		$this->surname = $_data[0]['surname'];
		$this->patronymic = $_data[0]['patronymic'];
		$this->groups = $_data[0]['groups'];
		$this->date = $_data[0]['date'];
	}

	public function setActivityByData($_data) {
		$this->events_activity_str = $_data[0]['events_activity'];
		
		$_arr = explode(";", $this->events_activity_str);
		$_len = count($_arr);
		for ($i = 0; $i < $_len; $i++) {
			$_arr_inner = explode("=", $_arr[$i]);
			$this->events_activity_id[$i] = $_arr_inner[0];
			if(!isset($_arr_inner[1]))
				$_arr_inner[1] = 0;
			$this->events_activity_val[$i] = $_arr_inner[1];
		}
		$this->events_activity_len = $_len;
	}
}
?>