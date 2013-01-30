<?php
include_once 'table_entity.php';
class IssuePropertiesData extends Table_Entity{
	public $table_name = 'issue_properties_data';
	
	public $id_order;			// provides get a serial index by id; id_order[ id ] = i;
	public $id;
	public $issue_id;
	public $property_id;
	public $data_value;
	public $date;
	
	public $data_value_by_property_id;
	
	public $entity_sheme_names	= Array('id',		'issue_id',		'property_id',		'data_value',	'date');
	
	public $entity_sheme_types	= Array('int',		'int',			'int',				'text',			'int');
	
	public $entity_sheme_form	= Array('void',		'text',			'text',				'text',			'text');
	
	public $entity_sheme_descr	= Array('Id',		'Issue id',		'Property id',		'Data alue', 	'Date');
	
	function IssuePropertiesData() {
		parent::Table_Entity();
	}

	protected function setValuesByData($_data) {
		$this->len = count($_data);
		for ($i = 0; $i < $this->len; $i++) {
			$this->id[$i]					= $_data[$i]['id'];
			$this->id_order[$this->id[$i]]	= $i;
			
			$this->issue_id[$i]				= $_data[$i]['issue_id'];
			$this->property_id[$i]			= $_data[$i]['property_id'];
			$this->data_value[$i]			= $_data[$i]['data_value'];
			$this->date[$i]					= $_data[$i]['date'];
			
			$this->data_value_by_property_id[$this->property_id[$i]]	= $_data[$i]['data_value'];
		}
	}
	
	public function getMainList() {
		$this->dat = $this->select_All_default();
		$this->setValuesByData($this->dat);
	}
	
	public function getOneItem($_id) {
		$this->dat = $this->select_One_by_id($_id);
		$this->setValuesByData($this->dat);
	}
	
	public function createNew() {
		/*$q ='INSERT INTO '.$this->table_name.'
				(id, name, date) VALUES(
					0,
					"New property",
					"'.date("Y-m-d H:i:s").'"
				)';
		$this->run_query($q);

		$this->setInfoMessage('Property successfully created');
		return mysql_insert_id();*/
	}
	
	public function updateItem($_id) {
		/*$_name = $_POST['name'];
		$_description = $_POST['description'];
		$_type = $_POST['type'];
		$_field_name = $_POST['field_name'];
		$_value = $_POST['value'];
		
		$q = 'UPDATE '.$this->table_name.' SET
			name = "'.$_name.'",
			type = "'.$_type.'",
			field_name = "'.$_field_name.'",
			value = "'.$_value.'",
			description = "'.$_description.'"
			WHERE id = '.$_id.' LIMIT 1;';
		
		$this->run_query($q);
		$this->setInfoMessage('Property successfully updated');*/
	}
	
	public function drop($_id) {
		/*parent::drop($_id);
		$this->setInfoMessage('Property successfully dropped');*/
	}
	
	public function getPropertiesByIssueId($_issue_id) {
		$q = "SELECT * FROM $this->table_name WHERE issue_id=$_issue_id ORDER BY id ASC;";
		$this->dat = $this->query_to_dat($q);
		$this->setValuesByData($this->dat);
	}
	
	public function updateOnePropety($_issue_id, $_prop_id, $_data_value) {
		$result = mysql_query("SELECT id FROM $this->table_name WHERE issue_id = $_issue_id AND property_id = $_prop_id;");
		if(mysql_num_rows($result) == 0){
			$this->createOnePropety($_issue_id, $_prop_id, $_data_value);
		}else{
			echo 'no';
			$q = 'UPDATE '.$this->table_name.' SET
			data_value = "'.$_data_value	.'",
			date = "'.date("Y-m-d H:i:s").'"
			WHERE issue_id = '.$_issue_id.' AND property_id = '.$_prop_id.' LIMIT 1;';
			$this->run_query($q);
		}
	}
	
	public function createOnePropety($_issue_id, $_prop_id, $_data_value) {
		$q = 'INSERT INTO '.$this->table_name.'
				(id, issue_id, property_id, data_value, date) VALUES(
					0,
					"'.$_issue_id.'",
					"'.$_prop_id.'",
					"'.$_data_value.'",
					"'.date("Y-m-d H:i:s").'"
				)';
		$this->run_query($q);
	}
	
}