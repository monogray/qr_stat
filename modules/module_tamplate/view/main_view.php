<?php
include_once 'view/view_core.php';
class WebStudioMainView extends View_Core{
	private $layout;
	private $form;

	function WebStudioMainView($_db_connect) {
		$this->setDBConnect($_db_connect);
		
		include_once 'modules/web_studio/view/templates/view_template_default.php';
		$template = new WebStudioDefaultTemplate();
		$this->setTemplate($template);
		
		include_once 'layouts/mono_layouts.php';
		$this->layout = new MonoLayouts();
	}
	
	protected function initFormsLayouts() {
		require_once 'layouts/form_layouts.php';
		$this->form = new FormsLayout();
	}
	
	public function draw() {
		$this->drawHeader();
			$this->drawTop();
			
			$this->process();
			
			$this->drawFooter();
		$this->drawEnd();
	}
	
	public function process() {
		/*if( Settings::isUserLogined() ){
			$this->drawMain();
		}else{
			$this->processLogin();
		}*/
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
		$this->layout->Draw_DivStart('', 'main_menu_container');
		for ($i = 0; $i < $_main_menu->len; $i++) {
			echo  '<div class="main_menu_item"><a href="?chapter='.$_main_menu->chapter[$i].'">'.$_main_menu->name[$i].'</a></div>';
		}
		$this->layout->Draw_DivEnd();
		
		for ($i = 0; $i < $_issue->len; $i++) {
			echo  $_issue->name[$i].'<br/>';
		}
	}
}
?>