<?php 
class WebStudioController{
	public $app_state;
	
	function WebStudioController() {
	}
	
	public function init($_app_state) {
		$this->app_state = $_app_state;
	}
	
	public function process() {
		$this->app_state->setActionInnerByPattern('index');			// sub_action actualy
			
		include_once 'modules/web_studio/controller/entities/index_controller.php';
		$controller = new IndexController($this->app_state);
		$controller->process();
	
		include_once 'modules/web_studio/view/main_view.php';
		$issueView = new WebStudioMainView($this->app_state);
		$issueView->setAppData( $controller->getEntity() );
		$issueView->draw();
	}
}