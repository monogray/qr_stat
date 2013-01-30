<?php 
class CoreController {
	public $app_state;
	
	public $entity;
	
	public $entity_name			= '';
	public $entity_class_path	= '';
	public $entity_actions		= Array('list', 'view');
	
	function CoreController($_app_state) {
		$this->app_state = $_app_state;
	}
	
	public function process() {
		if($this->app_state->getActionInner() == $this->entity_name){
			$this->process_index();
		}
		else if($this->app_state->getActionInner() == $this->entity_actions[0]){
			$this->process_list();
		}
		else if($this->app_state->getActionInner() == $this->entity_actions[1]){
			$this->process_view();
		}
	}
	
	public function process_index() {
		
	}
	
	public function process_list() {
		$this->entity->getMainList();
	}
	
	public function process_view() {
		$this->entity->getOneItem($this->app_state->getId());
	}
	
	// ----------------------------------------------------------------------------
	// SETTERS
	public function setEntity($_entity) {
		$this->entity = $_entity;
	}
	
	
	// ----------------------------------------------------------------------------
	// GETTERS
	public function getEntity() {
		return $this->entity;
	}
}