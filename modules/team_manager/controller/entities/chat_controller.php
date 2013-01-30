<?php 
include_once 'controller/entities/core_controller.php';
class ChatController extends CoreController {
	protected $entity_name			= 'chat';
	protected $entity_class_path	= 'modules/team_manager/model/bd_tables/chat.php';
	
	function ChatController($_app_state) {
		parent::CoreController($_app_state);
		
		include_once $this->entity_class_path;
		$_entity = new ChatModel();
		$this->setEntity($_entity);
	}
	
	public function process() {
		//parent::process();
		if($this->app_state->getAction() == ''){
			$this->processIndex();
		}else if($this->app_state->getAction() == 'edit'){
			$this->processEditChat();
		}else if($this->app_state->getAction() == 'send'){
			$this->processSendChatMessage();
		}else if($this->app_state->getAction() == 'history'){
			$this->processHistoryChat();
		}
	}
	
	protected function processIndex() {
		$this->entity->getChatList();
	}
	
	protected function processEditChat() {
		$this->entity->getChatMassagesList($this->app_state->getId());
		$this->entity->updateChatMessageCount(Settings::getCurrentUser()->id[0], $this->app_state->getId());
	}
	
	protected function processSendChatMessage() {
		$this->entity->sendChatMessage($this->app_state->getId());
		$this->entity->getChatMassagesList($this->app_state->getId());
		$this->entity->updateChatMessageCount(Settings::getCurrentUser()->id[0], $this->app_state->getId());
	}
	
	protected function processHistoryChat() {
		$this->entity->getChatMassagesList($this->app_state->getId());
	}
}