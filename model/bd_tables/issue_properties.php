<?php
include_once 'table_entity.php';
class IssueProperties extends Table_Entity{
	public $table_name = 'issue_properties';
	
	public $id_order;			// provides get a serial index by id; id_order[ id ] = i;
	public $id;
	public $name;
	public $type;
	public $field_name;
	public $value;
	public $description;
	public $date;

	public $name_by_id;
	
	
	public $entity_sheme_names	= Array('id',				'name',			'type',			'field_name',		'value',
										'description',		'date');
	
	public $entity_sheme_types	= Array('int',				'text',			'text',			'int',				'int',
										'int',				'date');
	
	public $entity_sheme_form	= Array('void',				'text',			'text',			'text',				'text',
										'textarea',			'info');
	
	public $entity_sheme_descr	= Array('Id',				'Name',			'Type',			'Fields name',		'Value',
										'Description',		'Date');
	
	function IssueProperties() {
		parent::Table_Entity();
	}

	protected function setValuesByData($_data) {
		$this->len = count($_data);
		for ($i = 0; $i < $this->len; $i++) {
			$this->id[$i]					= $_data[$i]['id'];
			$this->id_order[$this->id[$i]]	= $i;
			
			$this->name[$i]					= $_data[$i]['name'];
			$this->type[$i]					= $_data[$i]['type'];
			$this->field_name[$i]			= $_data[$i]['field_name'];
			$this->value[$i]				= $_data[$i]['value'];
			$this->description[$i]			= $_data[$i]['description'];
			$this->date[$i]					= $_data[$i]['date'];
			
			$this->name_by_id[ $this->id[$i] ]	= $_data[$i]['name'];
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
		$q ='INSERT INTO '.$this->table_name.'
				(id, name, date) VALUES(
					0,
					"New property",
					"'.date("Y-m-d H:i:s").'"
				)';
		$this->run_query($q);

		$this->setInfoMessage('Property successfully created');
		return mysql_insert_id();
	}
	
	public function updateItem($_id) {
		$_name = $_POST['name'];
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
		$this->setInfoMessage('Property successfully updated');
	}
	
	public function drop($_id) {
		parent::drop($_id);
		
		// Delete all related rows from issue_properties_data
		$q ='DELETE FROM '.$this->table_name.'_data WHERE property_id = '.$_id.';';
		$this->run_query($q);
		
		$this->setInfoMessage('Property successfully dropped');
	}
}