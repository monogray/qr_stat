<?php
include_once 'model/bd_tables/table_entity.php';
class ChatMessages extends Table_Entity{
	public $table_name = 'chat_massages';
	
	public $id_order;
	public $id;
	public $chat;
	public $message;
	public $author;
	public $date;
	
	public $author_entity;
	
	public $chat_one_entity;
	
	function ChatMessages() {
		parent::Table_Entity();
	}

	protected function setValuesByData($_data) {
		$this->len = count($_data);
		include_once 'users.php';
		for ($i = 0; $i < $this->len; $i++) {
			$this->id_order[$i]		= $i;
			$this->id[$i]			= $_data[$i]['id'];
			$this->chat[$i]			= $_data[$i]['chat'];
			$this->message[$i]		= htmlspecialchars_decode($_data[$i]['message']);
			$this->author[$i]		= $_data[$i]['author'];
			$this->date[$i]			= $_data[$i]['date'];
			
			//$this->author_entity[$i] = new Users();
			//$this->author_entity[$i]->getOneItem($this->author[$i]);
			$this->author_entity[$i] = new Users();
			$this->author_entity[$i]->setValuesByInstanceAndIdLazy($this->author[$i]);
		}
	}
	
	public function getMainListByChatId($_chat_id) {
		$this->dat = $this->query_to_dat("SELECT * FROM $this->table_name WHERE chat=$_chat_id ORDER BY date ASC;");
		$this->setValuesByData($this->dat);
		
		include_once 'chat.php';
		$this->chat_one_entity = new Chat();
		$this->chat_one_entity->getOneItem($_chat_id);
	}
	
	public function getMainListByChatIdAndLimit($_chat_id, $_limit) {
		$this->dat = $this->query_to_dat("SELECT * FROM (SELECT * FROM $this->table_name WHERE chat=$_chat_id ORDER BY date DESC LIMIT $_limit) AS t ORDER BY t.date ASC;");
		$this->setValuesByData($this->dat);
	
		include_once 'chat.php';
		$this->chat_one_entity = new Chat();
		$this->chat_one_entity->getOneItem($_chat_id);
	}
	
	public function getMainList() {
		$this->dat = $this->select_All_default();
		$this->setValuesByData($this->dat);
	}
	
	public function getCountByChatId($_chat_id) {
		$result = mysql_query("SELECT id FROM $this->table_name WHERE chat=$_chat_id;");
		return mysql_num_rows($result);
	}
	
	public function getOneItem($_id) {
		$this->dat = $this->select_One_by_id($_id);
		$this->setValuesByData($this->dat);
	}
	
	public function createNew() {
	}
	
	public function createNewByChatId($_id) {
		if(isset($_POST['message']) && $_POST['message'] != ''){
			$_message = htmlspecialchars($_POST['message']);
			$_author = Settings::getCurrentUser()->id[0];
			
			$q ='INSERT INTO '.$this->table_name.'
					(id, chat, message, author, date) VALUES(
						0,
						'.$_id.',
						"'.$_message.'",
						'.$_author.',
						"'.date("Y-m-d H:i:s").'"
					);';
			$this->run_query($q);
			return mysql_insert_id();
		}
	}
	
	public function updateItem($_id) {
	}
	
	public function drop($_id) {
		parent::drop($_id);
		$this->setInfoMessage('Issue successfully dropped');
	}
}