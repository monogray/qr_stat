<?php 
include_once 'table_entity.php';
class QR_manager_guests_groups extends Table_Entity{
	public $img_arr_path_pref = 'public/resources/guests/group/img_arr/';
	public $img_arr_path;
	
	public $id_order;	// $i to $id
	public $id;
	public $name;
	public $description;
	public $description_2;
	public $img_arr;
	public $img_arr_len;
	public $date;
	
	function QR_manager_guests_groups() {
		$this->table_name = 'qr_manager_guests_groups';
	}
	
	public function getFullList() {
		$this->dat = $this->select_All_default();
		$this->setValuesByData($this->dat);
	}
	
	public function getOneItem($_id) {
		$this->dat = $this->select_One_by_id($_id);
		$this->setValuesByData($this->dat);
	}
	
	public function getFirstOrderById() {
		$q = "SELECT * FROM $this->table_name ORDER BY id ASC LIMIT 1;";
		$result = $this->run_query($q);
		$row = mysql_fetch_array($result);
		return $row['id'];
	}
	
	public function setValuesByData($_data) {
		$this->len = count($_data);
		for ($i = 0; $i < $this->len; $i++) {
			$this->id[$i]					= $_data[$i]['id'];
			$this->id_order[ $this->id[$i] ]	= $i;
			$this->name[$i]					= $_data[$i]['name'];
			$this->description[$i]			= $_data[$i]['description'];
			$this->description_2[$i]		= $_data[$i]['description_2'];
			$this->date[$i]					= $_data[$i]['date'];
			$this->img_arr_path[$i]			= $this->img_arr_path_pref.$_data[$i]['id'].'/';
			
			$img_arr_str				= $_data[$i]['img_arr'];
			if( $img_arr_str != '' && $img_arr_str != null ){
				$this->img_arr[$i]		= explode(";", $img_arr_str);
				$this->img_arr_len[$i]	= count($this->img_arr[$i]);
			}else{
				$this->img_arr_len[$i] = 0;
			}
		}
	}
	
	public function createNew() {
		$q ='INSERT INTO '.$this->table_name.' (id, name, date) VALUES(
					0,
					"New Group",
					"'.date("Y-m-d H:i:s").'"
				)';
		$this->run_query($q);
		return mysql_insert_id();
	}
	
	public function createMany() {
		$_count = (int)$_POST['count'];
		if ( $_count > 0 ){
			$q ='INSERT INTO '.$this->table_name.' (id, name, date) VALUES(
					0,
					"New Group",
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
	
	public function updateItem($_id) {		
		$_name = $_POST['name'];
		$_description = $_POST['description'];
		$_description_2 = $_POST['description_2'];
		
		// Files and images processing
		include_once 'layouts/forms_processing.php';
		$formsProcessing = new FormsProcessing();
		$_files_list = $formsProcessing->FilesProcessing($this->img_arr_path_pref.$_id.'/', 'img_arr');
		
		$_img_arr_str = $this->getOneRowByIdAndName($_id, 'img_arr');
		if($_files_list != ''){
			if( $_img_arr_str != '' ){
				$_img_arr_final = $_img_arr_str.';'.$_files_list;
			}else{
				$_img_arr_final = $_files_list;
			}
		}else{
			$_img_arr_final = $_img_arr_str;
		}
		
		// Update query
		$q = 'UPDATE '.$this->table_name.' SET
			name = "'.$_name.'",
			description = "'.$_description.'",
			description_2 = "'.$_description_2.'",
			img_arr = "'.$_img_arr_final.'"
			WHERE id = '.$_id.' LIMIT 1;';
		$this->run_query($q);
	}
	
	
	public function dropImgArr($_id, $_img_arr_id) {
		$_img_arr_str = $this->getOneRowByIdAndName($_id, 'img_arr');
		$img_arr = explode(";", $_img_arr_str);
		
		unlink ( $this->img_arr_path_pref.$_id.'/'.$img_arr[$_img_arr_id] );
		unset($img_arr[$_img_arr_id]);
		
		$img_arr_str = implode(';', $img_arr);
		
		$q = 'UPDATE '.$this->table_name.' SET
			img_arr = "'.$img_arr_str.'"
			WHERE id = '.$_id.' LIMIT 1;';
		$this->run_query($q);
	}
	
}
?>