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
											'list_by_issue'		=> 'list_by_issue',
											'drop_img_arr'		=> 'drop_img_arr',
											'drop_img_arr_2'	=> 'drop_img_arr_2');
	
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
		}else if($this->app_state->getActionInner() == $this->entity_actions['drop_img_arr']){
			$this->processDropImgArr();
		}else if($this->app_state->getActionInner() == $this->entity_actions['drop_img_arr_2']){
			$this->processDropImgArr_2();
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
	
	protected function processDropImgArr() {
		$this->entity->dropImgArr($this->app_state->getId(), $this->app_state->getSubId(), 'img_arr');
		$this->processView();
	}
	
	protected function processDropImgArr_2() {
		$this->entity->dropImgArr($this->app_state->getId(), $this->app_state->getSubId(), 'img_arr_2');
		$this->processView();
	}
}