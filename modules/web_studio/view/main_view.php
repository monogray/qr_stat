<?php
include_once 'view/view_core.php';
class WebStudioMainView extends View_Core{

	function WebStudioMainView($_db_connect) {
		$this->setDBConnect($_db_connect);
		
		include_once 'modules/web_studio/view/templates/view_template_default.php';
		$template = new WebStudioDefaultTemplate();
		$this->setTemplate($template);
		
		include_once 'layouts/mono_layouts.php';
		$this->layout = new MonoLayouts();
		
		include_once 'layouts/html_entity/entity.php';
		include_once 'layouts/html_entity/div.php';
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
	/*protected function processLogin() {
		$this->initFormsLayouts();
		$this->form->formHeader( '' );
		$this->form->inputText('Логин', 30, '', 'login');
		$this->form->inputText('Пароль', 30, '', 'pass');
		$this->form->formSubmit('Save');
		$this->form->formFooter();
	}*/
	
	public function drawMain() {
		$_entity = $this->getAppData();
		$_main_menu = $_entity->getMainMenu();
		$_issue = $_entity->getIssue();
		
		// Main menu
		Entity::inst()->init('div', 'id', 'main_menu_container')->drawHeader();
			for ($i = 0; $i < $_main_menu->len; $i++) {
				echo  '<div class="main_menu_item"><a href="?chapter='.$_main_menu->chapter[$i].'">'.$_main_menu->name[$i].'</a></div>';
			}
		Entity::drawFooter_st('div', 'main_menu_container');
		
		Entity::inst()->init('div', 'id', 'main_container_caption')->drawHeader();
			echo $_main_menu->name[$_main_menu->is_current_chapter];
		Entity::drawFooter_st('div', 'main_container_caption');
		
		Entity::inst()->init('div', 'id', 'main_container')->drawHeader();
				
		for ($i = 0; $i < $_issue->len; $i++) {
			Entity::inst()->init('div', 'class', 'portfolio_item')->drawHeader();
			echo  $_issue->name[$i].'<br/>';
			echo  $_issue->summary[$i].'<br/>';
			
			$_img_len = count($_issue->img_arr_array[$i]);
			Entity::inst()->init('div', 'class', 'portfolio_img_container')->drawHeader();
			for ($j = 0; $j < $_img_len; $j++) {
				Entity::inst()->init('img', 'class', 'portfolio_img', 'self_closed')
				->addAttr('src', Settings::$path_to_attachments_dir.'issue/'.$_issue->id[$i].'/'.$_issue->img_arr_array[$i][$j])
				->draw();
			}
			Entity::drawFooter_st('div', 'portfolio_img_container');
			Entity::drawFooter_st('div', 'portfolio_item');
		}
		Entity::drawFooter_st('div', 'main_container');
	}
}
?>