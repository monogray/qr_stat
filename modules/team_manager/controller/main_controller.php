<?php 
class TeamManagerController{
	//protected $entity_name			= '';
	//protected $entity_class_path		= 'modules/web_studio/model/bd_tables/index.php';
	public $app_state;
	
	function TeamManagerController() {
	}
	
	public function init($_app_state) {
		$this->app_state = $_app_state;
	}
	
	public function process() {
		if( Settings::isUserLogined() ){
			$this->processLogined();
		}else{
			$this->processNonLogined();
		}
	}
	
	protected function processLogined() {
		if($this->app_state->getChapter() == 'index'){
			$this->app_state->setActionInnerByPattern('index');			// sub_action actualy
				
			include_once 'modules/team_manager/controller/entities/index_controller.php';
			$controller = new IndexController($this->app_state);
			$controller->process();
		
			include_once 'modules/team_manager/view/main_view.php';
			$issueView = new WebStudioMainView($this->app_state);
			$issueView->setAppData( $controller->getEntity() );
			$issueView->draw();
		}else if($this->app_state->getChapter() == 'chat'){
			$this->app_state->setActionInnerByPattern('chat');			// sub_action actualy
				
			include_once 'modules/team_manager/controller/entities/chat_controller.php';
			$controller = new ChatController($this->app_state);
			$controller->process();
		
			include_once 'modules/team_manager/view/main_view.php';
			$issueView = new WebStudioMainView($this->app_state);
			$issueView->setAppData( $controller->getEntity() );
			$issueView->drawChat();
		}else if($this->app_state->getChapter() == 'project'){
			$this->app_state->setActionInnerByPattern('project');		// sub_action actualy
				
			include_once 'modules/team_manager/controller/entities/project_controller.php';
			$controller = new ProjectController($this->app_state);
			$controller->process();
		
			include_once 'modules/team_manager/view/main_view.php';
			$issueView = new WebStudioMainView($this->app_state);
			$issueView->setAppData( $controller->getEntity() );
			$issueView->drawProjects();
		}else if($this->app_state->getChapter() == 'users'){
			$this->app_state->setActionInnerByPattern('users');		// sub_action actualy
				
			include_once 'modules/team_manager/controller/entities/users_controller.php';
			$controller = new UsersController($this->app_state);
			$controller->process();
		
			include_once 'modules/team_manager/view/main_view.php';
			$issueView = new WebStudioMainView($this->app_state);
			$issueView->setAppData( $controller->getEntity() );
			$issueView->drawUsers();
		}
	}
	
	protected function processNonLogined() {
		include_once 'modules/team_manager/view/main_view.php';
		$issueView = new WebStudioMainView($this->app_state);
		$issueView->drawLogin();
	}
}