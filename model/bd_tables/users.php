<?php
include_once 'table_entity.php';
class Users extends Table_Entity{
	public $table_name = 'users';
	
	public $id_order;		// provides get a serial index by id; id_order[ id ] = i;
	public $id;
	public $login;
	public $name;
	public $pass;
	public $mail;
	public $skype;
	public $icq;
	public $personal_data;
	public $date;
	
	public $is_current_chapter = -1;		// if false then '-1', if true - id of entity
	
	
	public $entity_sheme_names	= Array('id',			'login',	'name',		'pass',				'mail',
										'skype',		'icq',		'phone',	'personal_data',	'date');
	
	public $entity_sheme_types	= Array('int',			'text',		'text',		'text',				'text',
										'int',			'text',		'text',		'text',				'datetime');
	
	public $entity_sheme_form	= Array('void',			'text',		'text',		'text',				'text',
										'text',			'text',		'text',		'textarea',			'info');
	
	public $entity_sheme_descr	= Array('id',			'login',	'name',		'pass',				'mail',
										'skype',		'icq',		'phone',	'personal_data',	'date');
	
	function Users() {
		parent::Table_Entity();
	}

	protected function setValuesByData($_data) {
		$this->len = count($_data);
		for ($i = 0; $i < $this->len; $i++) {
			$this->id[$i]					= $_data[$i]['id'];
			$this->id_order[$this->id[$i]]	= $i;
			
			$this->login[$i]			= $_data[$i]['login'];
			$this->name[$i]				= $_data[$i]['name'];
			$this->pass[$i]				= $_data[$i]['pass'];
			$this->mail[$i]				= $_data[$i]['mail'];
			$this->skype[$i]			= $_data[$i]['skype'];
			$this->icq[$i]				= $_data[$i]['icq'];
			$this->phone[$i]			= $_data[$i]['phone'];
			$this->personal_data[$i]	= $_data[$i]['personal_data'];
			$this->date[$i]				= $_data[$i]['date'];

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
		/*$q ='INSERT INTO '.$this->table_name.'
				(id, name, lang, date) VALUES(
					0,
					"New Chapter",
					1,
					"'.date("Y-m-d H:i:s").'"
				)';
		$this->run_query($q);

		$this->setInfoMessage('Chapter successfully created');
		return mysql_insert_id();*/
	}
	
	public function updateItem($_id) {
		/*$_name = $_POST['name'];
		$_description = $_POST['description'];
		$_chapter = $_POST['chapter'];
		
		$q = 'UPDATE '.$this->table_name.' SET
			name = "'.$_name.'",
			description = "'.$_description.'",
			chapter = "'.$_chapter.'"
			WHERE id = '.$_id.' LIMIT 1;';
		
		$this->run_query($q);
		$this->setInfoMessage('Chapter successfully updated');*/
	}
	
	public function drop($_id) {
		parent::drop($_id);
		$this->setInfoMessage('Chapter successfully dropped');
	}
}