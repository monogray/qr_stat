<?php 
abstract class Table_Entity {
	public $table_name_pref	= '';
	public $table_name		= '';
	
	public $table_names = array('guests'			=> 'qr_manager_guests',
								'events'			=> 'qr_manager_guests_reg_types',
								'events_activity'	=> 'qr_manager_guests_events_activity',
								'groups'			=> 'qr_manager_guests_groups');
	public $len;
	public $dat;
	
	function Table_Entity() {
		// Combine full table name in variable $table_name
		$this->table_name_pref = Settings::$db_table_prefix_name;
		$this->table_name = $this->table_name_pref.$this->table_name;
	}

	abstract protected function setValuesByData($_data);
	abstract public function getMainList();
	abstract public function getOneItem($_id);
	abstract public function createNew();
	
	// ______________________________________________________________________________________________________________________
	// DB
	public function query_to_dat($_q) {
		$result = mysql_query($_q);
		$i = 0;
		$dat = Array();
		while( $result != null && $row = mysql_fetch_array($result) ){
			$dat[$i++] = $row;
		}
		return $dat;
	}
	
	public function run_query($_q) {
		return mysql_query($_q);
	}
	
	/**
	 * @return Array $dat
	 */
	public function select_All_default() {
		return $this->query_to_dat("SELECT * FROM $this->table_name ORDER BY id ASC;");
	}
	

	/**
	 * @param $_id
	 * @return $dat 
	 */
	public function select_One_by_id($_id) {
		return $this->query_to_dat("SELECT * FROM $this->table_name WHERE id = $_id LIMIT 1;");
	}
	
	/**
	 * @param $_id
	 */
	public function drop($_id) {
		$q ='DELETE FROM '.$this->table_name.' WHERE id = '.$_id.' LIMIT 1;';
		$this->run_query($q);
	}
	
	/**
	 * @param $_id
	 * @param $_row_name
	 * @return $dat[0][$_row_name] 
	 */
	public function getOneRowByIdAndName($_id, $_row_name) {
		$q = "SELECT $_row_name FROM $this->table_name WHERE id = $_id LIMIT 1;";
		$dat = $this->query_to_dat($q);
		return $dat[0][$_row_name];
	}
	
	/**
	 * @return int The number of rows in a result set on success & return false for failure;
	 */
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