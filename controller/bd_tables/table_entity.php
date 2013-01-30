<?php 
class Table_Entity {
	public $table_name;
	
	public $table_names = array('guests'			=> 'qr_manager_guests',
								'events'			=> 'qr_manager_guests_reg_types',
								'events_activity'	=> 'qr_manager_guests_events_activity',
								'groups'			=> 'qr_manager_guests_groups');
	public $len;
	public $dat;
	
	function Table_Entity() {
	}
	
	// ______________________________________________________________________________________________________________________
	// DB
	function query_to_dat($_q) {
		$result = mysql_query($_q);
		$i = 0;
		$dat = Array();
		while( $row = mysql_fetch_array($result) ){
			$dat[$i++] = $row;
		}
		return $dat;
	}
	
	function run_query($_q) {
		return mysql_query($_q);
	}
	
	function select_All_default() {
		return $this->query_to_dat("SELECT * FROM $this->table_name ORDER BY id ASC;");
	}
	
	function select_One_by_id($_id) {
		return $this->query_to_dat("SELECT * FROM $this->table_name WHERE id = $_id LIMIT 1;");
	}
	
	public function drop($_id) {
		$q ='DELETE FROM '.$this->table_name.' WHERE id = '.$_id.' LIMIT 1;';
		$this->run_query($q);
	}
	
	public function getOneRowByIdAndName($_id, $_row_name) {
		$q = "SELECT $_row_name FROM $this->table_name WHERE id = $_id LIMIT 1;";
		$dat = $this->query_to_dat($q);
		return $dat[0][$_row_name];
	}
	
	public function getRowsCount() {
		$result = mysql_query("SELECT id FROM $this->table_name;");
		return mysql_num_rows($result);
	}
	// END DB
	// ______________________________________________________________________________________________________________________
	
	// ______________________________________________________________________________________________________________________
	// INFO
	
	public function setInfoMessage($_message) {
		$_SESSION['message'] = $_message;
	}
	
	// END INFO
	// ______________________________________________________________________________________________________________________
}
?>