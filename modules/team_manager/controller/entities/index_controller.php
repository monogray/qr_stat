<?php 
include_once 'controller/entities/core_controller.php';
class IndexController extends CoreController {
	protected $entity_name			= 'index';
	protected $entity_class_path	= 'modules/team_manager/model/bd_tables/tracker.php';
	
	function IndexController($_app_state) {
		parent::CoreController($_app_state);
		
		include_once $this->entity_class_path;
		$_entity = new Tracker();
		$this->setEntity($_entity);
	}
	
	public function process() {
		//parent::process();
		if($this->app_state->getAction() == ''){
			$this->processIndex();
		}else if($this->app_state->getAction() == 'edit_task'){
			$this->processEditTask();
		}else if($this->app_state->getAction() == 'save_task'){
			$this->processSaveTask();
		}else if($this->app_state->getAction() == 'add_task'){
			$this->processAddTask();
		}else if($this->app_state->getAction() == 'update_filter'){
			$this->processUpdateFilter();
		}else if($this->app_state->getAction() == 'delete_attach'){
			$this->processDeleteAttach();
		}else if($this->app_state->getAction() == 'add_comment'){
			$this->processAddComment();
		}
	}
	
	protected function processLogin() {
	}
	
	protected function processIndex() {
		$this->entity->getTrackerPage();
	}
	
	protected function processEditTask() {
		$this->entity->getEditTaskPage( $this->app_state->getId() );
	}
	
	protected function processSaveTask() {
		$this->entity->saveTask( $this->app_state->getId() );
		$this->entity->getEditTaskPage( $this->app_state->getId() );
	}
	
	protected function processAddTask() {
		$_id = $this->entity->addTask();
		$this->entity->getEditTaskPage($_id);
	}
	
	protected function processUpdateFilter() {
		$this->entity->updateFilter($this->app_state->getId());
		$this->entity->getTrackerPage();
	}
	
	protected function processDeleteAttach() {
		$this->entity->deleteAttach( $_GET['attach_id'], $this->app_state->getId() );
		$this->entity->getEditTaskPage( $this->app_state->getId() );
	}
	
	protected function processAddComment() {
		$this->entity->addComment( $this->app_state->getId() );
		$this->entity->getEditTaskPage( $this->app_state->getId() );
	}
}