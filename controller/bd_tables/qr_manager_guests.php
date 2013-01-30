<?php
include_once 'table_entity.php';
class QR_manager_guests extends Table_Entity{
	public $id_order;
	public $id;
	public $name;
	public $surname;
	public $patronymic;
	public $groups_id;		// Current group id
	public $qr;				// Current group qr
	public $date;
	
	// Groups
	public $groups;			// Groups class
	public $groups_name;	// Current name
	
	public $events_activity;
	public $properties;
	public $reg_type;
	
	public $events_activity_str;
	public $events_activity_id = Array();
	public $events_activity_val = Array();
	public $events_activity_name = Array();
	public $events_activity_id_to_val = Array();	// index of a array is id
	public $events_activity_id_to_name = Array();	// index of a array is id
	public $events_activity_len;

	function QR_manager_guests() {
		$this->table_name = 'qr_manager_guests';
	}
	
	public function getMainList() {
		$this->dat = $this->select_All_default();
		$this->setValuesByData($this->dat);
	}
	
	public function getOneItem($_id) {
		$this->dat = $this->select_One_by_id($_id);
		$this->setValuesByData($this->dat);
		$this->setActivityByData($this->dat);
	}
	
	public function createNew() {
		include_once 'qr_manager_guests_groups.php';
		$this->groups = new QR_manager_guests_groups();
		$_groups = $this->groups->getFirstOrderById();

		$q ='INSERT INTO '.$this->table_name.'
				(id, name, groups, date) VALUES(
					0,
					"New Guest",
					"'.$_groups.'",
					"'.date("Y-m-d H:i:s").'"
				)';
		$this->run_query($q);

		$this->setInfoMessage('Guest successfully created');
		return mysql_insert_id();
	}
	
	// Package processing
	public function GuestPackAdd() {
		$_count = (int)$_POST['package_add'];
		if ( $_count > 0 ){
			include_once 'qr_manager_guests_groups.php';
			$this->groups = new QR_manager_guests_groups();
			$_groups = $this->groups->getFirstOrderById();
			
			$q ='INSERT INTO '.$this->table_name.'
					(id, name, groups, date) VALUES(
						0,
						"New Guest",
						"'.$_groups.'",
						"'.date("Y-m-d H:i:s").'"
					)';
			for ($i = 0; $i < $_count; $i++) {
				$this->run_query($q);
			}
			return true;
		}else{
			return false;
		}
	}
	
	public function GuestPackDrop() {
		if(isset($_POST['guests_pack'])){
			$_len = count($_POST['guests_pack']);
			for ($i = 0; $i < $_len; $i++) {
				$this->drop($_POST['guests_pack'][$i]);
			}
		}
	}
	
	public function GuestPackMoveToGroup() {
		if(isset($_POST['guests_pack'])){
			$_len = count($_POST['guests_pack']);
			for ($i = 0; $i < $_len; $i++) {
				$q = 'UPDATE '.$this->table_name.' SET
					groups = "'.$_POST['move_to_group'].'"
					WHERE id = '.$_POST['guests_pack'][$i].' LIMIT 1;';
				$this->run_query($q);
			}
		}
	}
	
	public function setValuesByData($_data) {
		include_once 'qr_manager_guests_groups.php';
		$this->groups = new QR_manager_guests_groups();
		$this->groups->getFullList();
		
		$this->len = count($_data);
		for ($i = 0; $i < $this->len; $i++) {
			$this->id_order[$i]				= $i;
			$this->id[$i]					= $_data[$i]['id'];
			$this->name[$i]					= $_data[$i]['name'];
			$this->surname[$i]				= $_data[$i]['surname'];
			$this->patronymic[$i]			= $_data[$i]['patronymic'];
			$this->groups_id[$i]			= $_data[$i]['groups'];
			$this->qr[$i]					= $_data[$i]['qr'];
			$this->date[$i]					= $_data[$i]['date'];
			
			// Groups
			if(isset( $this->groups->id_order[ $this->groups_id[$i] ] )){
				$_id = $this->groups->id_order[ $this->groups_id[$i] ];
				$this->groups_name[$i] = $this->groups->name[$_id];
			}
		}
	}
	
	public function setActivityByData($_data) {
		/*
		// Get raw properties string
		include_once 'qr_manager_guests_properties.php';
		$this->properties = new QR_manager_guests_properties();
		$this->properties->getOneItem_by_guest_id( $_data[0]['id'] );
		
		$this->events_activity_str = $this->properties->events_activity[0];
		
		// Convert raw string to Arrays
		$events_activity_id_tmp = Array();
		$events_activity_val = Array();
		$_arr = explode(";", $this->events_activity_str);
		$_len = count($_arr);
		for ($i = 0; $i < $_len; $i++) {
			$_arr_inner = explode("=", $_arr[$i]);
			$events_activity_id_tmp[$i] = $_arr_inner[0];
			if(!isset($_arr_inner[1]))
				$_arr_inner[1] = 0;
			$events_activity_val[ $i ] = $_arr_inner[1];
		}

		// Combine final Activitys arrays
		include_once 'qr_manager_guests_reg_types.php';
		$this->reg_type = new QR_manager_guests_reg_types();
		$this->reg_type->getFullList();
		for ($i = 0; $i < $this->reg_type->len; $i++) {
			$this->events_activity_name[$i] = $this->reg_type->name[$i];
			$this->events_activity_id[$i] = $this->reg_type->id[$i];
			$this->events_activity_id_to_name[ $this->events_activity_id[$i] ] = $this->reg_type->name[$i];
			
			$_id = array_search($this->events_activity_id[$i], $events_activity_id_tmp);
			if( $_id >= 0 ) {
				$this->events_activity_val[$i] = $events_activity_val[$_id];
				$this->events_activity_id_to_val[ $this->events_activity_id[$i] ] = $events_activity_val[$_id];
			}else{
				$this->events_activity_val[$i] = 0;
				$this->events_activity_id_to_val[ $this->events_activity_id[$i] ] = 0;
			}
		}
		
		$this->events_activity_len = $this->reg_type->len;
		
		*/
		
		// NEW ACTIVITY
		if(isset($_data[0]['id'])){
			include_once 'qr_manager_guests_events_activity.php';
			$this->events_activity = new QR_manager_guests_events_activity();
			$_activity_data = $this->events_activity->getActivitiesArrByGuestId( $_data[0]['id'] );
			
			//print_r($_activity_data[0]['activity']);
			
			include_once 'qr_manager_guests_reg_types.php';
			$this->reg_type = new QR_manager_guests_reg_types();
			$this->reg_type->getFullList();
			for ($i = 0; $i < $this->reg_type->len; $i++) {
				$this->events_activity_name[$i] = $this->reg_type->name[$i];
				$this->events_activity_id[$i] = $this->reg_type->id[$i];
				$this->events_activity_id_to_name[ $this->events_activity_id[$i] ] = $this->reg_type->name[$i];
				
				if(!isset($_activity_data[$i]['activity']))
					$_activity_data[$i]['activity'] = 0;
				$this->events_activity_val[$i] = $_activity_data[$i]['activity'];
				$this->events_activity_id_to_val[ $this->events_activity_id[$i] ] = $_activity_data[$i]['activity'];
			}
			
			$this->events_activity_len = $this->reg_type->len;
		}
		// END NEW ACTIVITY
	}
	
	public function updateItem($_id) {
		$_name = $_POST['name'];
		$_surname = $_POST['surname'];
		$_patronymic = $_POST['patronymic'];
		$_groups = $_POST['groups'];
		$_qr = $_POST['qr'];
		
		$q = 'UPDATE '.$this->table_name.' SET
			name = "'.$_name.'",
			surname = "'.$_surname.'",
			groups = "'.$_groups.'",
			patronymic = "'.$_patronymic.'",
			qr = "'.$_qr.'"
			WHERE id = '.$_id.' LIMIT 1;';
		
		$this->run_query($q);
		
		/*include_once 'qr_manager_guests_reg_types.php';
		$this->reg_type = new QR_manager_guests_reg_types();
		$this->reg_type->getFullList();
		$_reg_str = '';
		for ($i = 0; $i < $this->reg_type->len; $i++) {
			if( isset($_POST['events_activity']) ){
				if( in_array ( $this->reg_type->id[$i], $_POST['events_activity'] ) )
					$_val = 1;
				else
					$_val = 0;
			}else{
				$_val = 0;
			}
			$_reg_str .= $this->reg_type->id[$i].'='.$_val.';';
		}
		
		include_once 'qr_manager_guests_properties.php';
		$this->properties = new QR_manager_guests_properties();
		$this->properties->updateItem_eventsActivity($_id, $_reg_str);
		*/
		
		// NEW ACTIVITY
		include_once 'qr_manager_guests_events_activity.php';
		$this->events_activity = new QR_manager_guests_events_activity();
		
		include_once 'qr_manager_guests_reg_types.php';
		$this->reg_type = new QR_manager_guests_reg_types();
		$this->reg_type->getFullList();
		$_reg_str = '';
		for ($i = 0; $i < $this->reg_type->len; $i++) {
			if( isset($_POST['events_activity']) ){
				if( in_array ( $this->reg_type->id[$i], $_POST['events_activity'] ) )
					$_val = 1;
				else
					$_val = 0;
			}else{
				$_val = 0;
			}
			
			$this->events_activity->updateActivityByGuestsId($_id, $this->reg_type->id[$i], $_val);
		}
		// END NEW ACTIVITY
		
		$this->setInfoMessage('Guests data successfully updated');
	}
	
	public function GuestPackNormalizeQr() {
		$_data = $this->select_All_default();
		$_len = count($_data);
		for ($i = 0; $i < $_len; $i++) {
			$q = 'UPDATE '.$this->table_name.' SET
			qr = "'.($i+1).'"
			WHERE id = '.$_data[$i]['id'].' LIMIT 1;';
			$this->run_query($q);
		}
	}
	
	public function setRegistrationByid($_id, $_reg_id) {
		/*$this->getOneItem($_id);
		
		include_once 'qr_manager_guests_reg_types.php';
		$this->reg_type = new QR_manager_guests_reg_types();
		$this->reg_type->getFullList();
		$_reg_str = '';
		for ($i = 0; $i < $this->reg_type->len; $i++) {
			if( $this->reg_type->id[$i] == $_reg_id )
				$_val = 1;
			else
				$_val = $this->events_activity_id_to_val[ $this->reg_type->id[$i] ];
			$_reg_str .= $this->reg_type->id[$i].'='.$_val.';';
		}
		
		include_once 'qr_manager_guests_properties.php';
		$this->properties = new QR_manager_guests_properties();
		$this->properties->updateItem_eventsActivity($_id, $_reg_str);
		*/
		
		// NEW ACTIVITY
		$this->getOneItem($_id);
		
		include_once 'qr_manager_guests_events_activity.php';
		$this->events_activity = new QR_manager_guests_events_activity();
		
		include_once 'qr_manager_guests_reg_types.php';
		$this->reg_type = new QR_manager_guests_reg_types();
		$this->reg_type->getFullList();
		$_reg_str = '';
		for ($i = 0; $i < $this->reg_type->len; $i++) {
			if( $this->reg_type->id[$i] == $_reg_id ){
				$this->events_activity->updateActivityByGuestsId($_id, $this->reg_type->id[$i], $_val);
			}
		}
		// END NEW ACTIVITY
	}
	
	
	
	// GETTERS
	public function getAllGroupsIdList() {
		return $this->groups->id;
	}
	
	public function getAllGroupsNameList() {
		return $this->groups->name;
	}
}
?>