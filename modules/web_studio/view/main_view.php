<?php
include_once 'view/view_core.php';
class WebStudioMainView extends View_Core{

	function WebStudioMainView($_db_connect) {
		$this->setDBConnect($_db_connect);
		
		include_once 'modules/web_studio/view/templates/view_template_default.php';
		$template = new WebStudioDefaultTemplate();
		$this->setTemplate($template);
		
		$this->initFormsLayouts();
		$this->initLayout();
	}
	
	// Override
	protected function drawInHeader() {
		echo'<script type="text/javascript" src="modules/web_studio/public/js/index.js"></script>
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
		
		// Logo
		Entity::initAndDrawHeader_st('div', 'id', 'main_logo_container');
			$_logo_img = new Entity();
			$_logo_img->init('img', 'id', 'main_logo', 'self_closed')->addAttr('src', 'modules/web_studio/public/img/logo.png');
			
			Entity::inst()->init('a', 'id', 'main_logo_link')->addAttr('href', '?chapter=index')
				->addContent($_logo_img)
				->draw();
		Entity::drawFooter_st('div', 'main_logo_container');
		
		// Main menu
		Entity::inst()->init('div', 'id', 'main_menu_container')->drawHeader();
			for ($i = 0; $i < $_main_menu->len; $i++) {
				echo  '<div class="main_menu_item"><a href="?chapter='.$_main_menu->chapter[$i].'">'.$_main_menu->name[$i].'</a></div>';
			}
		Entity::drawFooter_st('div', 'main_menu_container');
		
		// Main container
		Entity::inst()->init('div', 'id', 'main_container_caption')->drawHeader();
			Entity::inst()->init('h1', 'class', 'portfolio_item_caption')->addContent($_main_menu->name[$_main_menu->is_current_chapter])->draw();
		Entity::drawFooter_st('div', 'main_container_caption');
		
		Entity::inst()->init('div', 'id', 'main_container')->drawHeader();
		
		Entity::inst()->init('div', 'id', 'portfolio_item_invisible_background')->draw();
		
		for ($i = 0; $i < $_issue->len; $i++) {
			Entity::inst()->init('div', 'class', 'portfolio_item')->drawHeader();
				
				$_current_val = $_issue->getPropertyValueByPropertieFieldsName($_issue->id[$i], 'is_caption_display');
				if($_current_val != 'false'){
					Entity::inst()->init('h2', 'class', 'portfolio_item_caption')->addContent($_issue->name[$i])->draw();
				}
				
				if(isset($_issue->img_arr_array_2[$i][0]) && $_issue->img_arr_array_2[$i][0] != ''){
					$_caption_img = Entity::inst()->init('img', 'class', 'item_image_caption_img')
						->addAttr('src', Settings::$path_to_attachments_dir.'issue/'.$_issue->id[$i].'/img_arr_2/'.$_issue->img_arr_array_2[$i][0])
						->toString();
					Entity::inst()->init('div', 'class', 'item_image_caption')->addContent($_caption_img)->draw();
				}
				
				Entity::inst()->init('div', 'class', 'portfolio_item_summary')->addContent($_issue->summary[$i])->draw();
				
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