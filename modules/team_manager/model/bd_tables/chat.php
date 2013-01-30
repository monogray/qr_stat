<?php
include_once 'model/bd_tables/table_entity.php';
class ChatModel extends Table_Entity{
	public $table_name = 'mono';
	
	private	$chat;
	public	$chat_messages;
	
	function ChatModel() {
	}
	
	protected function setValuesByData($_data) {
	}
	
	public function getMainList() {
	}
	
	public function getOneItem($_id) {
	}
	
	public function createNew() {
	}
	
	public function getChatList() {
		include_once 'model/task_traker/chat.php';
		$this->chat = Chat::getInstance();
	}
	
	public function getChatMassagesList($_id) {
		include_once 'model/task_traker/chat_messages.php';
		$this->chat_messages = new ChatMessages();
		$this->chat_messages->getMainListByChatId($_id);
	}
	
	public function sendChatMessage($_id) {
		include_once 'model/task_traker/chat_messages.php';
		$this->chat_messages = new ChatMessages();
		$this->chat_messages->createNewByChatId($_id);
	}
	
	function updateChatMessageCount($_user_id, $_chat_id) {
		include_once 'model/task_traker/chat_statistics.php';
		$this->chat = new ChatStatistics();
		$this->chat->updateChatMessageCount($_user_id, $_chat_id);
	}
	
	// GETTERS
	public function getChat() {
		return $this->chat;
	}
}