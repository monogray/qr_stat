<?php 
class CoreController {
	// package 'controller/app_state.php'
	protected $app_state;
	
	protected $entity;
	protected $entity_name			= '';
	protected $entity_class_path	= '';
	
	// Default actions
	protected $entity_actions		= Array('list'			=> 'list',
											'view'			=> 'view',
											'create'		=> 'create',
											'drop'			=> 'drop',
											'update'		=> 'update');
	
	function CoreController($_app_state) {
		$this->app_state = $_app_state;
	}
	
	protected function controllerArrayProcess($_controller_array){
		foreach ($_controller_array as $key => $value)
			if($this->getAppState()->getAction() == $key)
				$this->$value();
	}
	
	protected function controllerArrayProcessForInnerAction($_controller_array){
		foreach ($_controller_array as $key => $value)
			if($this->getActionInner() == $key)
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
	// HELPERS
	
	/**
	 *
	 * Matching '$this->getAppState()->getActionInner()' and '$_entity_actions'
	 *
	 * @param String $_entity_actions
	 * @return boolean
	 */
	protected function actionInnerMathWith($_entity_actions) {
		if($this->getAppState()->getActionInner() == $_entity_actions)
			return true;
		else
			return false;
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
	
	/**
	 * Get ActionInner from app_state variable ( $this->getAppState()->getActionInner() )
	 */
	public function getActionInner() {
		return $this->getAppState()->getActionInner();
	}
}