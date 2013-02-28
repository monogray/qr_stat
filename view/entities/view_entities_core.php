<?php
class ViewEntitiesCore {
	private $app_data;
	private $app_state;
	
	protected $form;
	
	private $template;

	function ViewEntitiesCore() {
	}
	
	public function setTemplate($_template='default') {
		if($_template == 'default'){
			include_once 'view/templates/view_template_default.php';
			$this->template = new DefaultTemplate();
		}
	}
	
	protected function initFormsLayouts() {
		require_once 'layouts/form_layouts.php';
		$this->form = new FormsLayout();
	}
	
	protected function drawHeader() {
		echo '<!DOCTYPE html>
			<html>
				<head>
					<title>'.$this->template->title.'</title>
				
					<meta http-equiv="Content-Type" content="text/html; charset=Windows-1251" />
					<meta name="description" content="'.$this->template->description.'" />
					<meta name="keywords" http-equiv="keywords" content="'.$this->template->keywords.'"/>
			
					<link rel="stylesheet" type="text/css" href="'.$this->template->stylesheet_path.'" />
					<script type="text/javascript" src="'.$this->template->jquery_path.'"></script>';
		
		$this->drawInHeader();
		
		echo '</head>
	
			<body>';
	}
	
	protected function drawInHeader() {
	}
	
	protected function drawTop() {
	}
	
	protected function drawFooter() {
	}
	
	protected function drawEnd() {
		echo '</body>
		</html>';
	}
	
	// HELPERS
	public function setUrl($_chapter, $_action='', $_id='', $_sub_id='') {
		if($_action == '')
			return '?chapter='.$_chapter;
		else if($_id == '')
			return '?chapter='.$_chapter.'&amp;action='.$_action;
		else if($_sub_id == '')
			return '?chapter='.$_chapter.'&amp;action='.$_action.'&amp;id='.$_id;
		else
			return '?chapter='.$_chapter.'&amp;action='.$_action.'&amp;id='.$_id.'&amp;sub_id='.$_sub_id;
	}
	
	/**
	 * 
	 * Matching '$this->getAppState()->getActionInner()' and '$_entity_actions'
	 * 
	 * @param String $_entity_actions
	 * @return boolean
	 */
	protected function actionInnerMathWith($_entity_actions) {
		if($this->getAppState()->getActionInner() == $_entity_actions)
			return true;
		else
			return false;
	}
	
	protected function controllerArrayProcessForInnerAction($_controller_array){
		foreach ($_controller_array as $key => $value)
			if($this->getActionInner() == $key)
				$this->$value();
	}
	

	// SETTERS
	public function setAppData($_app_data) {
		$this->app_data = $_app_data;
	}
	
	public function setAppState($_app_state) {
		$this->app_state = $_app_state;
	}

	
	// GETTERS
	public function getAppData() {
		return $this->app_data;
	}
	
	function getAppState() {
		return $this->app_state;
	}
	
	/**
	 * Get ActionInner from app_state variable ( $this->getAppState()->getActionInner() )
	 */
	public function getActionInner() {
		return $this->getAppState()->getActionInner();
	}
}
?>