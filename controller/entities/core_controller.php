<?php 
class CoreController {
	protected $app_state;
	
	protected $entity;
	
	protected $entity_name			= '';
	protected $entity_class_path	= '';
	protected $entity_actions		= Array('list'			=> 'list',
											'view'			=> 'view',
											'create'		=> 'create',
											'drop'			=> 'drop',
											'update'		=> 'update');
	
	function CoreController($_app_state) {
		$this->app_state = $_app_state;
	}
	
	public function controllerArrayProcess($_controller_array){
		foreach ($_controller_array as $key => $value)
			if($this->getAppState()->getAction() == $key)
			$this->$value();
	}
	
	public function process() {
		if($this->app_state->getActionInner() == $this->entity_name){
			$this->processIndex();
		}
		else if($this->app_state->getActionInner() == $this->entity_actions['list']){
			$this->processList();
		}
		else if($this->app_state->getActionInner() == $this->entity_actions['view']){
			$this->processView();
		}
		else if($this->app_state->getActionInner() == $this->entity_actions['create']){
			$this->processCreate();
		}
		else if($this->app_state->getActionInner() == $this->entity_actions['drop']){
			$this->processDrop();
		}
		else if($this->app_state->getActionInner() == $this->entity_actions['update']){
			$this->processUpdate();
		}
	}
	
	protected function processIndex() {
		$this->processList();
	}
	
	protected function processList() {
		$this->entity->getMainList();
	}
	
	protected function processView() {
		$this->entity->getOneItem($this->app_state->getId());
	}
	
	protected function processCreate() {
		$this->entity->createNew();
		$this->entity->getMainList();
	}
	
	protected function processDrop() {
		$this->entity->drop($this->app_state->getId());
		$this->entity->getMainList();
	}
	
	protected function processUpdate() {
		$this->entity->updateItem($this->app_state->getId());
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
	
	public function getAppState() {
		return $this->app_state;
	}
}