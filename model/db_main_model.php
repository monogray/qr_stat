<?php 
class DbMainModel {
	// Tables names
	private $guests_tab_name = 'qr_manager_guests';
	private $guests_properties_tab_name = 'qr_manager_guests_properties';
	private $events_types_tab_name = 'qr_manager_guests_reg_types';

	function DbMainModel() {
	}
	
	
	// ______________________________________________________________________________________________________________________
	// qr_manager_guests

	function guests_GET_List() {
		$q = "SELECT * FROM $this->guests_tab_name ORDER BY id ASC;";
		$result = mysql_query($q);
		$i = 0;
		while( $row = mysql_fetch_array($result) ){
			$dat[$i++] = $row;
		}
		return $dat;
	}
	
	function guests_GET_List_byId($_id) {
		$q = "SELECT * FROM $this->guests_tab_name WHERE id = $_id ORDER BY id ASC;";
		$result = mysql_query($q);
		$row = mysql_fetch_array($result);
		return $row;
	}
	
	function guests_UPDATE_List_byId($_id) {
		$_name = $_POST['name'];
		$_surname = $_POST['surname'];
		$_patronymic = $_POST['patronymic'];
	
		$q = 'UPDATE '.$this->guests_tab_name.' SET
			name = "'.$_name.'",
			surname = "'.$_surname.'",
			patronymic = "'.$_patronymic.'"
			WHERE id = '.$_id.' LIMIT 1;';
		mysql_query($q);
		
		$dat = $this->regType_GET_typeList();
		$_len = count($dat);
		$_reg_str = '';
		for ($i = 0; $i < $_len; $i++) {
			if( isset($_POST['events_activity']) ){
				if( in_array ($dat[$i]['id'], $_POST['events_activity']) )
					$_val = 1;
				else 
					$_val = 0;
			}else{
				$_val = 0;
			}
			$_reg_str .= $dat[$i]['id'].'='.$_val.';';
		}
		$this->guests_UPDATE_EventsActivity_byId($_id, $_reg_str);
	}
	
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
	
	// ______________________________________________________________________________________________________________________
	// END qr_manager_guests
	
	
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
	
	function regType_GET_typeList_byId($_id) {
		$q = "SELECT * FROM $this->events_types_tab_name WHERE id = $_id ORDER BY id ASC;";
		$result = mysql_query($q);
		$row = mysql_fetch_array($result);
		return $row;
	}
	
	function regType_UPDATE_typeList_byId($_id) {
		$_name = $_POST['name'];
		$_description = $_POST['description'];
		
		$q = 'UPDATE '.$this->events_types_tab_name.' SET
			name = "'.$_name.'",
			description = "'.$_description.'"
			WHERE id = '.$_id.' LIMIT 1;';
		mysql_query($q);
	}
	
	function regType_NEW_typeList() {
		$q ='INSERT INTO '.$this->events_types_tab_name.'
				(id, name, date) VALUES(
				0,
				"New Event",
				"'.date("Y-m-d H:i:s").'"
			)';
		mysql_query($q);
		
		return mysql_insert_id();
	}
	
	// ______________________________________________________________________________________________________________________
	// END qr_manager_guests_reg_types
	
}
?>