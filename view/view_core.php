<?php
class View_Core {
	private $db_connect;
	private $app_data;
	private $app_state;
	
	private $template;
	
	protected $form;
	protected $layout;

	function View_Core($_db_connect) {
		$this->setDBConnect($_db_connect);
	}
	
	public function setTemplate($_template='default') {
		if($_template == 'default'){
			include_once 'view/templates/view_template_default.php';
			$this->template = new DefaultTemplate();
		}else{
			$this->template = $_template;
		}
	}
	
	public function drawHeader() {
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
	
	public function drawInHeader() {
	}
	
	public function drawTop() {
	}
	
	public function drawFooter() {
	}
	
	public function drawEnd() {
		echo '</body>
		</html>';
	}
	
	/**
	 * Draw login form
	 */
	protected function processLogin() {
	}
	
	protected function initFormsLayouts() {
		require_once 'layouts/form_layouts.php';
		$this->form = new FormsLayout();
	}
	
	function initLayout() {
		include_once 'layouts/mono_layouts.php';
		$this->layout = new MonoLayouts();
	}
	
	// HELPERS
	function setUrl($_chapter, $_action='', $_id='', $_sub_id='') {
		if($_action == '')
			return 'index.php?chapter='.$_chapter;
		else if($_id == '')
			return 'index.php?chapter='.$_chapter.'&amp;action='.$_action;
		else if($_sub_id == '')
			return 'index.php?chapter='.$_chapter.'&amp;action='.$_action.'&amp;id='.$_id;
		else
			return 'index.php?chapter='.$_chapter.'&amp;action='.$_action.'&amp;id='.$_id.'&amp;sub_id='.$_sub_id;
	}
	
	// Setters
	function setDBConnect($_db_connect) {
		$this->db_connect = $_db_connect;
	}
	
	function setAppData($_app_data) {
		$this->app_data = $_app_data;
	}
	
	function setAppState($_app_state) {
		$this->app_state = $_app_state;
	}

	// Getters
	function getDBConnect() {
		return $this->db_connect;
	}
	
	function getAppData() {
		return $this->app_data;
	}
	
	function getAppState() {
		return $this->app_state;
	}
}
?>