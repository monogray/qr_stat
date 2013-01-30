<?php 
include_once 'view_entities.php';
class IssuePropertiesView extends ViewEntities {
	protected $entity_name			= 'issue_properties';
	protected $chapter_name			= 'Issue properties';
	
	function IssuePropertiesView($_app_state) {
		parent::ViewEntities();
		
		$this->setAppState($_app_state);
		$this->setTemplate('default');
	}
	
	public function process() {
		parent::process();
	}
}