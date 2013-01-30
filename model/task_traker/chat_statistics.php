<?php
include_once 'model/bd_tables/table_entity.php';
class ChatStatistics extends Table_Entity{
	public $table_name = 'chat_statistics';
	
	public $id_order;
	public $id;
	public $user_id;
	public $chat_id;
	public $messages_count;
	public $date;
	
	function ChatStatistics() {
		parent::Table_Entity();
	}

	protected function setValuesByData($_data) {
		$this->len = count($_data);
		for ($i = 0; $i < $this->len; $i++) {
			$this->id[$i]				= $_data[$i]['id'];
			$this->id_order[ $this->id[$i] ] = $i;
			$this->user_id[$i]			= $_data[$i]['user_id'];
			$this->chat_id[$i]			= $_data[$i]['chat_id'];
			$this->messages_count[$i]	= $_data[$i]['messages_count'];
			$this->date[$i]				= $_data[$i]['date'];
		}
	}
	
	public function getMainList() {
		$this->dat = $this->select_All_default();
		$this->setValuesByData($this->dat);
	}
	
	public function getMessageCountByUserIdAndChatId($_user_id, $_chat_id) {
		$result = mysql_query("SELECT messages_count FROM $this->table_name WHERE user_id=$_user_id AND chat_id=$_chat_id ORDER BY id ASC;");
		$row = mysql_fetch_array($result);
		return $row['messages_count'];
	}
	
	public function getOneItem($_id) {
		$this->dat = $this->select_One_by_id($_id);
		$this->setValuesByData($this->dat);
	}
	
	public function createNew() {
	}
	
	public function createByUserIdAndChatId($_user_id, $_chat_id) {
		$q ='INSERT INTO '.$this->table_name.'
				(id, user_id, chat_id) VALUES(
					0,
					'.$_user_id.',
					'.$_chat_id.'
				)';
		$this->run_query($q);
	}
	
	public function updateItem($_id) {
	}
	
	public function updateChatMessageCount($_user_id, $_chat_id) {
		// Create row if not exist
		$result = mysql_query("SELECT id FROM $this->table_name WHERE chat_id=$_chat_id AND user_id=$_user_id;");
		if(mysql_num_rows($result) == 0){
			$this->createByUserIdAndChatId($_user_id, $_chat_id);
		}
		
		// Get messages count
		include_once 'model/task_traker/chat_messages.php';
		$this->chat_messages = new ChatMessages();
		$_mes_count = $this->chat_messages->getCountByChatId($_chat_id);

		// Update messages count
		$q = 'UPDATE '.$this->table_name.' SET
			messages_count	= '.$_mes_count.',
			date			= "'.date("Y-m-d H:i:s").'"		
			WHERE user_id = '.$_user_id.' AND chat_id='.$_chat_id.' LIMIT 1;';
		$this->run_query($q);
	}
	
	public function drop($_id) {
		parent::drop($_id);
		$this->setInfoMessage('Issue successfully dropped');
	}
}