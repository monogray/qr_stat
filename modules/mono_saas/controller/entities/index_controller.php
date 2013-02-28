<?php 
include_once 'controller/entities/core_controller.php';
class IndexController extends CoreController {
	protected $entity_name			= 'index';
	protected $entity_class_path	= 'modules/mono_saas/model/bd_tables/index.php';
	
	function IndexController($_app_state) {
		parent::CoreController($_app_state);
		
		include_once $this->entity_class_path;
		$issue = new Index();
		$this->setEntity($issue);
	}
	
	public function process() {
		//parent::process();
		if($this->app_state->getChapter() == 'index'){
			$this->processIndex();
		}
	}
	
	protected function processIndex() {
		$this->entity->getPage();
	}
}