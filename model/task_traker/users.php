<?php
include_once 'model/bd_tables/table_entity.php';
class Users extends Table_Entity{
	public $table_name = 'users';
	static public $instance = null;
	
	public $id_order;
	public $id;
	public $login;
	public $name;
	public $pass;
	public $mail;
	public $skype;
	public $icq;
	public $phone;
	public $personal_data;
	public $date;
	
	public $user_rights;
	
	function Users() {
		parent::Table_Entity();
	}
	
	static public function getInstance(){
		if(self::$instance == null){
			self::$instance = new self();
			self::$instance->getMainList();
		}
		return self::$instance;
	}
	
	protected function setValuesByData($_data) {
		$this->len = count($_data);
		include_once 'users_issue_rights.php';
		for ($i = 0; $i < $this->len; $i++) {
			$this->id[$i]			= $_data[$i]['id'];
			$this->id_order[ $this->id[$i] ] = $i;
			$this->login[$i]		= $_data[$i]['login'];
			$this->name[$i]			= $_data[$i]['name'];
			$this->pass[$i]			= $_data[$i]['pass'];
			$this->mail[$i]			= $_data[$i]['mail'];
			$this->skype[$i]		= $_data[$i]['skype'];
			$this->icq[$i]			= $_data[$i]['icq'];
			$this->phone[$i]		= $_data[$i]['phone'];
			$this->personal_data[$i]= $_data[$i]['personal_data'];
			$this->date[$i]			= $_data[$i]['date'];
			
			$this->user_rights[$i] = new UsersRights();
			$this->user_rights[$i]->setValuesByInstanceAndUserIdLazy($this->id[$i]);
		}
	}
	
	public function setValuesByInstanceAndIdLazy($_id) {
		$_instance = self::getInstance();
		if(isset( $_instance->id_order[$_id] )){
			$__id = $_instance->id_order[$_id];
		}else{
			$__id = 0;
		}
		$this->id[0]			= $_instance->id[$__id];
		$this->login[0]			= $_instance->login[$__id];
		$this->name[0]			= $_instance->name[$__id];
		$this->pass[0]			= $_instance->pass[$__id];
		$this->mail[0]			= $_instance->mail[$__id];
		$this->skype[0]			= $_instance->skype[$__id];
		$this->icq[0]			= $_instance->icq[$__id];
		$this->phone[0]			= $_instance->phone[$__id];
		$this->personal_data[0]	= $_instance->personal_data[$__id];
		$this->date[0]			= $_instance->date[$__id];
		
		$this->user_rights[0]	= $_instance->user_rights[$__id];
		
		$this->len = 1;
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
					"New issue",
					1,
					"'.date("Y-m-d H:i:s").'"
				)';
		$this->run_query($q);

		$this->setInfoMessage('Issue successfully created');
		return mysql_insert_id();*/
	}
	
	public function updateItem($_id) {
		/*$_name = $_POST['name'];
		$_summary = $_POST['summary'];
		$_description = $_POST['description'];
		$_description_2 = $_POST['description_2'];
		$_menu = $_POST['menu'];
		
		$q = 'UPDATE '.$this->table_name.' SET
			name			= "'.$_name.'",
			summary			= "'.$_summary.'",
			description		= "'.$_description.'",
			description_2	= "'.$_description_2.'",
			menu			= "'.$_menu.'"
			WHERE id = '.$_id.' LIMIT 1;';
		
		$this->run_query($q);

		$this->propertiesUpdate($_id);		
		$this->setInfoMessage('Issue successfully updated');*/
	}
	
	public function drop($_id) {
		parent::drop($_id);
		$this->setInfoMessage('User successfully dropped');
	}
}