<?php 
include_once 'core_controller.php';
class MainMenuController extends CoreController {
	protected $entity_name			= 'main_menu';
	protected $entity_class_path	= 'model/bd_tables/main_menu.php';
	
	/*protected $entity_sheme			= Array('list'		=> 'list',
											'view'		=> 'view',
											'create'	=> 'create',
											'drop'		=> 'drop');*/
	
	function MainMenuController($_app_state) {
		parent::CoreController($_app_state);
		
		include_once $this->entity_class_path;
		$mainMenu = new MainMenu();
		$this->setEntity($mainMenu);
	}
	
	public function process() {
		parent::process();
	}
}