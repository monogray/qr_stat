<?php 
include_once 'controller/bd_tables/table_entity.php';
class QR_manager_events_statistics extends Table_Entity{
	
	public $qr_manager_guests_events_activity;
	public $qr_manager_guests_reg_types;
	public $qr_manager_guests;
	
	public $dates;
	public $dates_full;
	public $dates_count;
	public $dates_guests_ids;
	
	public $guests;									// Array of QR_manager_guests type
	public $events;									// Array of QR_manager_guests_reg_types type
	
	function QR_manager_events_statistics() {
		
	}
	
	public function getDateList($_events_id) {
		include_once 'controller/bd_tables/qr_manager_guests_reg_types.php';
		$this->events = new QR_manager_guests_reg_types();
		$this->events->getOneItem($_events_id);
		
		include_once 'controller/bd_tables/qr_manager_guests_events_activity.php';
		$this->qr_manager_guests_events_activity = new QR_manager_guests_events_activity();
		
		$q = "SELECT * FROM ".$this->qr_manager_guests_events_activity->table_name." WHERE events_id=$_events_id AND activity <> 0 ORDER BY activity_date ASC;";
		$_data = $this->query_to_dat($q);
		
		$date_tmp = '';
		$j = -1;
		$k = 0;
		
		$_len = count($_data);
		for ($i = 0; $i < $_len; $i++) {
			$current_date = date("d.m.Y", strtotime( $_data[$i]['activity_date'] ) );
			if($date_tmp != $current_date){
				$j++;
				$date_tmp =  $current_date;
				$this->dates[$j] = $date_tmp;
				$this->dates_full[$j] = $_data[$i]['activity_date'];
				$this->dates_count[$j] = 1;
				$this->dates_guests_ids[$j][$k++] = $_data[$i]['guests_id'];
			}else{
				$this->dates_count[$j]++;
				$this->dates_guests_ids[$j][$k++] = $_data[$i]['guests_id'];
			}
		}
		
		$this->len = count($this->dates);
	}
	
	public function getGueststListByDate($_events_id) {
		include_once 'controller/bd_tables/qr_manager_guests.php';
		
		include_once 'controller/bd_tables/qr_manager_guests_reg_types.php';
		$this->events = new QR_manager_guests_reg_types();
		$this->events->getOneItem($_events_id);
		
		include_once 'controller/bd_tables/qr_manager_guests_events_activity.php';
		$this->qr_manager_guests_events_activity = new QR_manager_guests_events_activity();
		
		$q = "SELECT * FROM ".$this->qr_manager_guests_events_activity->table_name." WHERE events_id=$_events_id AND activity <> 0 ORDER BY activity_date ASC;";
		$_data = $this->query_to_dat($q);
		
		$_len = count($_data);
		for ($i = 0; $i < $_len; $i++) {
			$this->dates[$i] = date("d.m.Y", strtotime( $_data[$i]['activity_date'] ) );
			$this->dates_full[$i] = $_data[$i]['activity_date'];
			$this->dates_guests_ids[$i] = $_data[$i]['guests_id'];
			$this->guests[$i] = new QR_manager_guests();
			$this->guests[$i]->getOneItem($_data[$i]['guests_id']);
		}
		
		$this->len = count($this->dates);
	}
	
}
?>