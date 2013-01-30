<?php 
include_once 'controller/entities/core_controller.php';
class ProjectController extends CoreController {
	protected $entity_name			= 'project';
	protected $entity_class_path	= 'modules/team_manager/model/bd_tables/projects.php';
	
	function ProjectController($_app_state) {
		parent::CoreController($_app_state);
		
		include_once $this->entity_class_path;
		$_entity = new ProjectsModel();
		$this->setEntity($_entity);
	}
	
	public function process() {
		if($this->app_state->getAction() == ''){
			$this->processIndex();
		}else if($this->app_state->getAction() == 'edit'){
			$this->processProjectEdit();
		}else if($this->app_state->getAction() == 'save'){
			$this->processProjectSave();
		}else if($this->app_state->getAction() == 'add_project'){
			$this->processAddProject();
		}
	}
	
	protected function processLogin() {
	}
	
	protected function processIndex() {
		$this->entity->getProjectsList();
	}
	
	protected function processProjectEdit() {
		$this->entity->getProjectById($this->app_state->getId());
	}
	
	protected function processProjectSave() {
		$this->entity->updateProjectById($this->app_state->getId());
		$this->entity->getProjectById($this->app_state->getId());
	}
	
	protected function processAddProject() {
		$_id = $this->entity->createProject();
		$this->entity->getProjectById($_id);
	}
}