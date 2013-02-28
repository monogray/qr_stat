<?php
include_once 'view/view_core.php';
class WebStudioMainView extends View_Core{

	function WebStudioMainView($_db_connect) {
		$this->setDBConnect($_db_connect);
		
		include_once Settings::$path_to_current_module.'view/templates/view_template_default.php';
		$template = new WebStudioDefaultTemplate();
		$this->setTemplate($template);
		
		$this->initLayout();
		$this->initFormsLayouts();
	}
	
	// Override
	protected function drawInHeader() {
		echo'<script type="text/javascript" src="modules/mono_saas/public/js/index.js"></script>
			<script type="text/javascript">
			</script>';
	}
	
	public function draw() {
		$this->drawHeader();
		$this->drawTop();
		
		$this->process();
		
		$this->drawFooter();
		$this->drawEnd();
	}
	
	public function process() {
		$this->drawMain();
	}
	
	/**
	 * Draw login form
	 */
	protected function processLogin() {
		$this->initFormsLayouts();
		$this->form->formHeader( '' );
		$this->form->inputText('Логин', 30, '', 'login');
		$this->form->inputText('Пароль', 30, '', 'pass');
		$this->form->formSubmit('Save');
		$this->form->formFooter();
	}
	
	public function drawMain() {
		$_entity = $this->getAppData();
		$_main_menu = $_entity->getMainMenu();
		$_issue = $_entity->getIssue();
		
		// Main menu
		Entity::inst()->init('div', 'id', 'main_menu_container')->drawHeader();
		Entity::inst()->init('div', 'id', 'main_menu_container_inner')->drawHeader();
		for ($i = 0; $i < $_main_menu->len; $i++) {
			echo  '<div class="main_menu_item"><a href="?chapter='.$_main_menu->chapter[$i].'">'.$_main_menu->name[$i].'</a></div>';
		}
		Entity::drawFooter_st('div', 'main_menu_container_inner');
		Entity::drawFooter_st('div', 'main_menu_container');
		
		for ($i = 0; $i < $_issue->len; $i++) {
			echo  $_issue->name[$i].'<br/>';
		}
	}
}