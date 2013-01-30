<?php 
include_once 'core_controller.php';
class IssueController extends CoreController {
	protected $entity_name			= 'issue';
	protected $entity_class_path	= 'model/bd_tables/issue.php';
	
	// Override
	protected $entity_actions		= Array('list'				=> 'list',
											'view'				=> 'view',
											'create'			=> 'create',
											'drop'				=> 'drop',
											'update'			=> 'update',
											'list_by_menu'		=> 'list_by_menu',
											'create_by_menu'	=> 'create_by_menu',
											'create_by_issue'	=> 'create_by_issue',
											'list_by_issue'		=> 'list_by_issue');
	
	function IssueController($_app_state) {
		parent::CoreController($_app_state);
		
		include_once $this->entity_class_path;
		$this->setEntity(new Issue());
	}
	
	public function process() {
		parent::process();
		if($this->app_state->getActionInner() == $this->entity_actions['list_by_menu']){
			$this->processListByMenu();
		}else if($this->app_state->getActionInner() == $this->entity_actions['create_by_menu']){
			$this->processCreateByMenu();
		}else if($this->app_state->getActionInner() == $this->entity_actions['create_by_issue']){
			$this->processCreateByIssue();
		}else if($this->app_state->getActionInner() == $this->entity_actions['list_by_issue']){
			$this->processListByIssue();
		}
	}
	
	protected function processListByMenu() {
		$this->entity->getListByChapterId($this->app_state->getId());
	}
	
	protected function processCreateByMenu() {
		$_id = $this->entity->createNewByChapterId($this->app_state->getId());
		$this->entity->getOneItem($_id);
	}
	
	protected function processCreateByIssue() {
		$_id = $this->entity->createNewByParentIssueChapterId($this->app_state->getId());
		$this->entity->getOneItem($_id);
	}
	
	protected function processListByIssue() {
		$this->entity->getListByParentIssueId($this->app_state->getId());
	}
}