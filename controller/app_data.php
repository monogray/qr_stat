<?php 
class AppData {
	public $data;

	public $qr_manager_guests;
	public $qr_manager_guests_reg_types;
	public $qr_manager_guests_groups;
	public $qr_manager_events_statistics;
	
	function AppState() {
	}
	
	public function killData() {
		$this->data = Array();
	}
	
	// SETTERS
	public function setDataById($_id, $_val) {
		$this->data[$_id] = $_val;
	}
	
	public function addDataById($_id, $_ind_name, $_val) {
		$this->data[$_id][$_ind_name] = $_val;
	}
	
	public function set_QR_manager_guest($_qr_manager_guests) {
		$this->qr_manager_guests = $_qr_manager_guests;
	}
	
	public function set_QR_manager_guest_reg_types($_qr_manager_guests_reg_types) {
		$this->qr_manager_guests_reg_types = $_qr_manager_guests_reg_types;
	}
	
	public function set_QR_manager_guest_groups($_qr_manager_guests_groups) {
		$this->qr_manager_guests_groups = $_qr_manager_guests_groups;
	}
	
	public function set_QR_manager_events_statistics($_qr_manager_events_statistics) {
		$this->qr_manager_events_statistics = $_qr_manager_events_statistics;
	}
	
	
	
	// GETTERS
	public function getData() {
		return $this->data;
	}
	
	public function get_QR_manager_guest() {
		return $this->qr_manager_guests;
	}
	
	public function get_QR_manager_guest_reg_types() {
		return $this->qr_manager_guests_reg_types;
	}
	
	public function get_QR_manager_guest_groups() {
		return $this->qr_manager_guests_groups;
	}
	
	public function get_QR_manager_events_statistics() {
		return $this->qr_manager_events_statistics;
	}
}
?>