<?php 
include_once 'core_controller.php';
class ÑontentController extends CoreController {
	protected $entity_name			= 'content';
	protected $entity_class_path	= 'model/saas/content_model.php';
	protected $entity_class_name	= 'ContentModel';
	
	// Override
	protected $entity_actions		= Array('index'				=> 'processIndex',
											'main_menu_add'		=> 'processMainMenuAdd',
											'main_menu_view'	=> 'processMainMenuView',
											'issue_view'		=> 'processIssueView',
											'issue_update'		=> 'processIssueUpdate',
											'issue_add'			=> 'processIssueAdd',
											'issue_drop'		=> 'processIssueDrop');
	
	function ÑontentController($_app_state) {
		parent::CoreController($_app_state);
		
		include_once $this->entity_class_path;
		$this->setEntity( new $this->entity_class_name() );		// proccess as 'new Issue()' or 'new MainMenu()' etc.
	}
	
	public function process() {
		//parent::process();
		$this->controllerArrayProcessForInnerAction($this->entity_actions);
	}
	
	protected function processIndex() {
		$this->entity->getIndex();
	}
	
	// MAIN MENU
	protected function processMainMenuAdd() {
		//$this->entity->mainMenuAdd();
		$this->entity->getIndex();
	}
	
	protected function processMainMenuView() {
		$this->entity->getMainMenuView( $this->getAppState()->getId() );
	}
	
	// ISSUE
	protected function processIssueView() {
		$this->entity->getMainIssueView( $this->getAppState()->getId() );
	}
	
	protected function processIssueUpdate() {
		$this->entity->processIssueUpdate( $this->getAppState()->getId() );
	}
	
	protected function processIssueAdd() {
		$this->entity->processIssueAdd( $this->getAppState()->getId(), $_GET['type'] );
	}
	
	protected function processIssueDrop() {
		$this->entity->processIssueDrop( $this->getAppState()->getId() );
	}
}