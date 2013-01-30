<?php 
include_once 'core_controller.php';
class IssuePropertiesController extends CoreController {
	protected $entity_name			= 'issue_properties';
	protected $entity_class_path	= 'model/bd_tables/issue_properties.php';
	
	function IssuePropertiesController($_app_state) {
		parent::CoreController($_app_state);
		
		include_once $this->entity_class_path;
		$this->setEntity(new IssueProperties());
	}
	
	public function process() {
		parent::process();
	}
}