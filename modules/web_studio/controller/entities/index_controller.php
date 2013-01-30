<?php 
include_once 'controller/entities/core_controller.php';
class IndexController extends CoreController {
	protected $entity_name			= 'index';
	protected $entity_class_path	= 'modules/web_studio/model/bd_tables/index.php';
	
	function IndexController($_app_state) {
		parent::CoreController($_app_state);
		
		include_once $this->entity_class_path;
		$issue = new Index();
		$this->setEntity($issue);
	}
	
	public function process() {
		//parent::process();
		
		if( Settings::isUserLogined() ){
			if($this->app_state->getChapter() == 'index'){
				$this->processIndex();
			}
			/*else if($this->app_state->getActionInner() == $this->entity_actions['list']){
				$this->processList();
			}*/
		}else{
			if($this->app_state->getChapter() == 'index'){
				$this->processIndex();
			}
		}
	}
	
	protected function processIndex() {
		$this->entity->getPage();
	}
}