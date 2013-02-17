<?php 
include_once 'controller/entities/core_controller.php';
class UsersController extends CoreController {
	protected $entity_name			= 'users';
	protected $entity_class_path	= 'modules/team_manager/model/bd_tables/users_model.php';
	
	function UsersController($_app_state) {
		parent::CoreController($_app_state);
		
		include_once $this->entity_class_path;
		$_entity = new UsersModel();
		$this->setEntity($_entity);
	}
	
	public function process() {
		$_controller = Array(	''			=> 'processIndex',
								'edit'		=> 'processUsersEdit');
		$this->controllerArrayProcess($_controller);
	}
	
	protected function processLogin() {
	}
	
	protected function processIndex() {
		$this->entity->getUsersList();
	}
	
	protected function processUsersEdit() {
		$this->entity->getUsersById($this->getAppState()->getId());
	}
}