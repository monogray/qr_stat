<?php
include_once 'model/bd_tables/table_entity.php';
class ProjectsModel extends Table_Entity{
	public $table_name = 'mono';
	
	private	$project;
	
	function ProjectsModel() {
	}
	
	protected function setValuesByData($_data) {
	}
	
	public function getMainList() {
	}
	
	public function getOneItem($_id) {
	}
	
	public function createNew() {
	}
	
	public function getProjectsList() {
		include_once 'model/task_traker/project.php';
		$this->project = new Project();
		$this->project->getMainList();
	}
	
	public function getProjectById($_id) {
		include_once 'model/task_traker/project.php';
		$this->project = new Project();
		$this->project->getOneItem($_id);
	}
	
	public function updateProjectById($_id) {
		include_once 'model/task_traker/project.php';
		$this->project = new Project();
		$this->project->updateItem($_id);
	}
	
	public function createProject() {
		include_once 'model/task_traker/project.php';
		$this->project = new Project();
		return $this->project->createNew();
	}
	
	// GETTERS
	public function getProject() {
		return $this->project;
	}
}