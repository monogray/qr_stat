<?php
class AppProcessing {
	// Tables names
	private $guests_tab_name = 'qr_manager_guests';
	private $guests_properties_tab_name = 'qr_manager_guests_properties';
	private $events_types_tab_name = 'qr_manager_guests_reg_types';
	
	function AppProcessing() {
	}
	
	function process() {
		if( isset($_GET['action']) && isset($_GET['id']) && isset($_GET['regtype_id']) ){
			if($_GET['action'] == 'registration'){
				$this->registrationProcess();
			}
		}
	}
	
	function registrationProcess() {
		$_id = $_GET['id'];
		$_regtype_id = $_GET['regtype_id'];
		$_result = '';
		
		$dat = $this->regType_GET_typeList();
		$dat_len = count($dat);
		//print_r($dat);
		
		$_act_str = $this->guests_GET_EventsActivity_byId($_id);
		$_act_str = $_act_str['events_activity'];
		
		$_arr = explode(";", $_act_str);
		$_len = count($_arr);
		for ($i = 0; $i < $_len-1; $i++) {
			$_arr_inner = explode("=", $_arr[$i]);
			
			if(!isset($_arr_inner[1]))
				$_arr_inner[1] = 0;
			$events_activity[$_arr_inner[0]] = $_arr_inner[1];
			
			/*$events_activity_id[$i] = $_arr_inner[0];
			
			if(!isset($_arr_inner[1]))
				$_arr_inner[1] = 0;
			
			if($events_activity_id[$i] != $_regtype_id)
				$events_activity_val[$i] = $_arr_inner[1];
			else
				$events_activity_val[$i] = 1;*/
			
			//$_result .= $events_activity_id[$i].'='.$events_activity_val[$i].';';
		}
		
		for ($i = 0; $i < $dat_len; $i++) {
			if($dat[$i]['id'] == $_regtype_id)
				$_val = 1;
			else{
				if(isset($events_activity[$dat[$i]['id']]))
					$_val = $events_activity[$dat[$i]['id']];
				else
					$_val = 0;
			}
			$_result .= $dat[$i]['id'].'='.$_val.';';
		}
		
		$this->guests_UPDATE_EventsActivity_byId($_id, $_result);
	}
	
	
	// ______________________________________________________________________________________________________________________
	// qr_manager_guests
	function guests_GET_EventsActivity_byId($_id) {
		$q = "SELECT * FROM $this->guests_properties_tab_name WHERE guests_id = $_id ORDER BY id ASC;";
		$result = mysql_query($q);
		$row = mysql_fetch_array($result);
		return $row;
	}
	
	function guests_UPDATE_EventsActivity_byId($_id, $_events_activity) {
		// Check if exist
		if( !$this->guests_check_IfEventsActivityExist_guestsId($_id) ){
			$q ='INSERT INTO '.$this->guests_properties_tab_name.'
				(id, guests_id, events_activity) VALUES(
				0,
				'.$_id.',
				"'.$_events_activity.'"
			)';
			mysql_query($q);
		}else{
			$q = 'UPDATE '.$this->guests_properties_tab_name.' SET
				events_activity = "'.$_events_activity.'"
				WHERE guests_id = '.$_id.' LIMIT 1;';
			mysql_query($q);
		}
	}
	
	function guests_check_IfEventsActivityExist_guestsId($_id) {
		$result = mysql_query("SELECT COUNT(*) FROM $this->guests_properties_tab_name WHERE guests_id = '$_id';");
		$row = mysql_fetch_array($result);
		if($row[0] > 0)
			return true;
		else
			return false;
	}
	// END qr_manager_guests_reg_types
	// ______________________________________________________________________________________________________________________
	
	
	// ______________________________________________________________________________________________________________________
	// qr_manager_guests_reg_types
	function regType_GET_typeList() {
		$q = "SELECT * FROM $this->events_types_tab_name ORDER BY id ASC;";
		$result = mysql_query($q);
		$i = 0;
		while( $row = mysql_fetch_array($result) ){
			$dat[$i++] = $row;
		}
		return $dat;
	}
	// END qr_manager_guests_reg_types
	// ______________________________________________________________________________________________________________________
}
?>