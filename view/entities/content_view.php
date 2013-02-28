<?php 
include_once 'view_entities.php';
class IssueView extends ViewEntities {
	protected $entity_name			= 'content';
	protected $chapter_name			= 'Content';
	
	protected $entity_actions		= Array('index'				=> 'processIndex',
											'main_menu_add'		=> '',
											'main_menu_view'	=> 'processMainMenuView',
											'issue_view'		=> 'processIssueView',
											'issue_update'		=> 'processIssueView',
											'issue_add'			=> 'processMainMenuView',
											'issue_drop'		=> 'processMainMenuView');
	
	function IssueView($_app_state) {
		parent::ViewEntities();
		
		$this->setAppState($_app_state);
		$this->setTemplate('default');
	}
	
	public function process() {
		//parent::process();
		$this->controllerArrayProcessForInnerAction($this->entity_actions);
	}
	
	protected function drawMainMenu($_main_menu) {
		// Main menu
		Entity::inst()->init('div', 'id', 'main_menu_container')->drawHeader();
			Entity::inst()->init('div', 'id', 'main_menu_container_inner')->drawHeader();
			
			$_main_menu_settings_link = new Entity();
			$_main_menu_settings_link->init('a', 'id', 'main_menu_settings_link')->addAttr('href', '?chapter=admin&action=content_index')->addContent('Settings');
			Entity::inst()->init('div', 'class', 'main_menu_item')->addContent($_main_menu_settings_link)->draw();
			
			for ($i = 0; $i < $_main_menu->len; $i++) {
				echo  '<div class="main_menu_item"><a href="?chapter=admin&action=content_main_menu_view&id='.$_main_menu->id[$i].'">'.$_main_menu->name[$i].'</a></div>';
			}
			
			Entity::drawFooter_st('div', 'main_menu_container_inner');
		Entity::drawFooter_st('div', 'main_menu_container');
	}
	
	protected function processIndex() {
		$_entity = $this->getAppData();
		$_main_menu = $_entity->getMainMenu();
		
		$this->drawMainMenu($_main_menu);
		
		Entity::inst()->init('div', 'id', 'main_content_container')->drawHeader();
			Entity::inst()->init('div', 'id', 'main_content_add_container')->drawHeader();
			
			Entity::inst()->init('a', 'id', 'add_propertie_link')->addAttr('href', '?chapter=admin&action=content_main_menu_add')->addContent('+ Add chapter')->draw();
			
			Entity::drawFooter_st('div', 'main_content_add_container');
		Entity::drawFooter_st('div', 'main_content_container');
	}
	
	// MAIN MENU
	protected function processMainMenuView() {
		$_entity = $this->getAppData();
		$_main_menu = $_entity->getMainMenu();
		$_issue = $_entity->getIssue();
		
		$this->drawMainMenu($_main_menu);
		
		Entity::inst()->init('div', 'id', 'main_content_container')->drawHeader();
		
			Entity::inst()->init('div', 'id', 'property_link_container')->drawHeader();
				Entity::inst()->init('a', 'class', 'main_menu_add_property_link')->addAttr('href', '?chapter=admin&action=content_issue_add&id='.$_GET['id'].'&type=1')->addContent('+ Add portfolio')->draw();
				Entity::inst()->init('a', 'class', 'main_menu_add_property_link')->addAttr('href', '?chapter=admin&action=content_issue_add&id='.$_GET['id'].'&type=2')->addContent('+ Add text block')->draw();
			Entity::drawFooter_st('div', 'property_link_container');
			
			for ($i = 0; $i < $_issue->len; $i++) {
				if($_issue->type[$i] == 1){
					$this->drawIssueItem_portfolio($_issue, $i);
				}else if($_issue->type[$i] == 2){
					$this->drawIssueItem_textOneCol($_issue, $i);
				}
			}
			
		Entity::drawFooter_st('div', 'main_content_container');
		
		echo '<script>
			$(".drop_property_link").click(function() {
				if (confirm("Drop?")) {
					var _id = $(this).attr("issue_id");
					window.location = "?chapter=admin&action=content_issue_drop&id="+_id;
				}
			});
		</script>';
	}
	
	protected function drawIssueItem_portfolio($_issue, $i) {
		Entity::inst()->init('div', 'class', 'issue_item_container')->addAttr('css_class', 'portfolio')->drawHeader();
			Entity::inst()->init('h2')->addContent('['.$_issue->id[$i].'] '.$_issue->name[$i])->draw();
				
			Entity::inst()->init('div', 'class', 'issue_item_content_container')->drawHeader();
				
			// Actions buttons
			Entity::inst()->init('a', 'class', 'add_property_link')->addAttr('href', '?chapter=admin&action=content_issue_view&id='.$_issue->id[$i])->addContent('Edit')->draw();
			Entity::inst()->init('a', 'class', 'drop_property_link')->addAttr('href', '#')->addAttr('issue_id', $_issue->id[$i])->addContent('Drop')->draw();
				
			// Summary
			Entity::inst()->init('div', 'class', 'issue_item_text_container')->drawHeader();
			echo $_issue->summary[$i];
			Entity::drawFooter_st('div', 'issue_item_text_container');
				
			// Image array
			$_len = count($_issue->img_arr_array[$i]);
			for ($j = 0; $j < $_len; $j++) {
				Entity::inst()->init('img', 'class', 'issue_item_img_arr_img', 'self_closed')
				->addAttr('src', Settings::$path_to_attachments_dir.'issue/'.$_issue->id[$i].'/'.$_issue->img_arr_array[$i][$j])
				->addAttr('height', '50px')
				->draw();
			}
			
			Entity::drawFooter_st('div', 'issue_item_content_container');
		Entity::drawFooter_st('div', 'issue_item_container');
	}
	
	protected function drawIssueItem_textOneCol($_issue, $i) {
		Entity::inst()->init('div', 'class', 'issue_item_container')->addAttr('css_class', 'text_one_col')->drawHeader();
			Entity::inst()->init('h2')->addContent('['.$_issue->id[$i].'] '.$_issue->name[$i])->draw();
		
			Entity::inst()->init('div', 'class', 'issue_item_content_container')->drawHeader();
		
			// Actions buttons
			Entity::inst()->init('a', 'class', 'add_property_link')->addAttr('href', '?chapter=admin&action=content_issue_view&id='.$_issue->id[$i])->addContent('Edit')->draw();
			Entity::inst()->init('a', 'class', 'drop_property_link')->addAttr('href', '#')->addAttr('issue_id', $_issue->id[$i])->addContent('Drop')->draw();
		
			// Summary
			if($_issue->summary[$i] != ''){
				Entity::inst()->init('div', 'class', 'issue_item_text_container')->drawHeader();
					echo $_issue->summary[$i];
				Entity::drawFooter_st('div', 'issue_item_text_container');
			}
			
			// Image array
			$_len = count($_issue->img_arr_array[$i]);
			for ($j = 0; $j < $_len; $j++) {
				Entity::inst()->init('img', 'class', 'issue_item_img_arr_img', 'self_closed')
				->addAttr('src', Settings::$path_to_attachments_dir.'issue/'.$_issue->id[$i].'/'.$_issue->img_arr_array[$i][$j])
				->addAttr('height', '50px')
				->draw();
			}
				
			Entity::drawFooter_st('div', 'issue_item_content_container');
		Entity::drawFooter_st('div', 'issue_item_container');
	}
	
	// ISSUE
	protected function processIssueView() {
		$_entity = $this->getAppData();
		$_main_menu = $_entity->getMainMenu();
		$_issue = $_entity->getIssue();
		
		$this->drawMainMenu($_main_menu);
		
		Entity::inst()->init('div', 'id', 'main_content_container')->drawHeader();
		
		if($_issue->type[0] == 1)
			$this->processIssueView_portfolio($_issue);
		else if($_issue->type[0] == 2)
			$this->processIssueView_text_one_col($_issue);
			
		Entity::drawFooter_st('div', 'main_content_container');
	}
	
	protected function processIssueView_portfolio($_entity) {
		$_id = $_entity->id[0];
		Entity::inst()->init('h2')->addContent($_entity->name[0])->draw();
		
		$this->initFormsLayouts();
		$this->form->formHeader( '?chapter=admin&action=content_issue_update&id='.$_id.'&type='.$_entity->type[0] );
		$this->form->formSubmit('Save');
		
		Entity::inst()->init('div', 'class', 'block_area_2')->drawHeader();
			$this->form->inputText('Name ', 30, $_entity->name[0], 'name');
		Entity::drawFooter_st('div', 'block_area_2');
		
		Entity::inst()->init('div', 'class', 'block_area_2')->drawHeader();
			$this->form->inputTextarea('Summary ', 80, 20, $_entity->summary[0], 'summary', 'edit_textfield');
		Entity::drawFooter_st('div', 'block_area_2');
		
		// Attachments
		// 1 array
		Entity::inst()->init('div', 'class', 'block_area_2')->drawHeader();
		Entity::inst()->init('h2')->setContent('Images array 1')->draw();
		
		$this->form->inputFile('Attchments ', 'img_arr[]', true);
			
		Entity::inst()->init('div', 'class', 'block_area_2')->drawHeader();
		$_len = count($_entity->img_arr_array[0]);
		for ($i = 0; $i < $_len; $i++) {
			Entity::initAndDrawHeader_st('div', 'class', 'img_arr_item');
			Entity::inst()->init('img', 'class', 'img_arr_img', 'self_closed')
			->addAttr('src', Settings::$path_to_attachments_dir.'issue/'.$_id.'/'.$_entity->img_arr_array[0][$i])
			->addAttr('width', '150px')
			->draw();
				
			//$_drop_link = Entity::inst()->init('a', 'class', 'img_arr_drop_link')->addAttr('href', '?chapter=admin&action=issue_drop_img_arr&id='.$_id.'&sub_id='.$i)->addContent('drop')->toString();
			//Entity::initAndAddContentAndDraw_st('div', 'class', 'drop_img_arr', 'closed', $_drop_link);
			
			Entity::drawFooter_st('div', 'img_arr_item');
		}
		Entity::drawFooter_st('div', 'block_area_2');
			
		Entity::drawFooter_st('div', 'block_area_2');
		
		$this->form->formSubmit('Save');
		$this->form->formFooter();
	}
	
	protected function processIssueView_text_one_col($_entity) {
		$_id = $_entity->id[0];
		Entity::inst()->init('h2')->addContent($_entity->name[0])->draw();
	
		$this->initFormsLayouts();
			$this->form->formHeader( '?chapter=admin&action=content_issue_update&id='.$_id.'&type='.$_entity->type[0] );
		$this->form->formSubmit('Save');
	
		Entity::inst()->init('div', 'class', 'block_area_2')->drawHeader();
			$this->form->inputText('Name ', 30, $_entity->name[0], 'name');
		Entity::drawFooter_st('div', 'block_area_2');
	
		Entity::inst()->init('div', 'class', 'block_area_2')->drawHeader();
			$this->form->inputTextarea('Summary ', 80, 20, $_entity->summary[0], 'summary', 'edit_textfield');
		Entity::drawFooter_st('div', 'block_area_2');
	
		// Attachments
		// 1 array
		Entity::inst()->init('div', 'class', 'block_area_2')->drawHeader();
			Entity::inst()->init('h2')->setContent('Images array 1')->draw();
		
			$this->form->inputFile('Attchments ', 'img_arr[]', true);
				
			Entity::inst()->init('div', 'class', 'block_area_2')->drawHeader();
			$_len = count($_entity->img_arr_array[0]);
			for ($i = 0; $i < $_len; $i++) {
				Entity::initAndDrawHeader_st('div', 'class', 'img_arr_item');
				Entity::inst()->init('img', 'class', 'img_arr_img', 'self_closed')
				->addAttr('src', Settings::$path_to_attachments_dir.'issue/'.$_id.'/'.$_entity->img_arr_array[0][$i])
				->addAttr('width', '150px')
				->draw();
		
				//$_drop_link = Entity::inst()->init('a', 'class', 'img_arr_drop_link')->addAttr('href', '?chapter=admin&action=issue_drop_img_arr&id='.$_id.'&sub_id='.$i)->addContent('drop')->toString();
				//Entity::initAndAddContentAndDraw_st('div', 'class', 'drop_img_arr', 'closed', $_drop_link);
					
				Entity::drawFooter_st('div', 'img_arr_item');
			}
			Entity::drawFooter_st('div', 'block_area_2');
		Entity::drawFooter_st('div', 'block_area_2');
	
		$this->form->formSubmit('Save');
		$this->form->formFooter();
	}
	
	
	/*protected function processListByMenu() {
		$_entity = $this->getAppData();
		
		$this->processListByMenu_header($_entity);
	
		echo '<table class="list_table">
				<tr><td>#</td><td>Id</td><td>Name</td><td>Edit</td><td>Date</td><td>View Issues list</td><td>Add Issue</td><td>Drop</td></tr>';
		for ($i = 0; $i < $_entity->len; $i++) {
			echo '<tr>';
			echo '<td>'.($i+1).'</td>';
			echo '<td>'.$_entity->id[$i].'</td>';
			echo '<td>';
				echo $_entity->name[$i];
			echo '</td>';
			echo '<td>';
				$this->default_layouts->Draw_HyperLink($this->setUrl('admin', $this->entity_name.'_'.$this->entity_actions['view'], $_entity->id[$i]), 'Edit', 'list_link');
			echo '</td>';
				
			echo '<td>'.$_entity->date[$i].'</td>';
			
			echo '<td>';
				$this->default_layouts->Draw_HyperLink($this->setUrl('admin', $this->entity_name.'_'.$this->entity_actions['list_by_issue'], $_entity->id[$i]), 'View', 'list_link');
			echo '</td>';
				
			echo '<td>';
				$this->default_layouts->Draw_HyperLink($this->setUrl('admin', $this->entity_name.'_'.$this->entity_actions['create_by_issue'], $_entity->id[$i]), 'Add', 'list_link');
			echo '</td>';
			
			echo '<td>';
				$this->default_layouts->Draw_HyperLink($this->setUrl('admin', $this->entity_name.'_'.$this->entity_actions['drop'], $_entity->id[$i]), 'Drop', 'list_link');
			echo '</td>';
				
			echo '</tr>';
		}
		echo '<table>';
	}
	
	protected function processListByMenu_header($_entity) {
		$_caption = '';
		$_id = '';
		if(isset($_entity->menu_cur_entity[0]->name[0])){
			$_caption = $_entity->menu_cur_entity[0]->name[0];
			$_id = $_entity->menu_cur_entity[0]->id[0];
			$_create_new_link = 'index.php?chapter=admin&action=issue_create_by_menu&id='.$_id;
		}else if(isset($_entity->issue_cur_entity[0]->name[0])){
			$_caption = $_entity->issue_cur_entity[0]->name[0];
			$_id = $_entity->issue_cur_entity[0]->id[0];
			$_create_new_link = 'index.php?chapter=admin&action=issue_create_by_issue&id='.$_id;
		}
		
		$this->default_layouts->Draw_H1(' Issues list of "'.$_caption.'" (id '.$_id.')');
	
		$this->default_layouts->Draw_DivStart('block_area_2', '');
			$this->default_layouts->Draw_HyperLink($this->setUrl('admin'), '&larr; Administration', 'list_link_back');
			$this->default_layouts->Draw_HyperLink($this->setUrl('admin', 'main_menu'), '&larr; Main menu', 'list_link_back');
		$this->default_layouts->Draw_DivEnd();
	
		$this->default_layouts->Draw_DivStart('', '');
			//$this->default_layouts->Draw_HyperLink($this->setUrl('admin', $this->entity_name.'_'.$this->entity_actions['create']), '+ New', 'list_link');
			$this->default_layouts->Draw_HyperLink($_create_new_link, '+ Add New', 'list_link');
		$this->default_layouts->Draw_DivEnd();
	}
	
	protected function processView() {
		$_entity = $this->getAppData();
		$_id = $_entity->id[0];
	
		Entity::inst()->init('h1')->setContent($this->chapter_name.' view. (id '.$_id.')')->draw();
		
		// Get Parent link
		if($_entity->menu[0] != -1)
			$_par_link = '?chapter=admin&action=issue_list_by_menu&id='.$_entity->menu[0];
		else 
			$_par_link = '?chapter=admin&action=issue_list_by_issue&id='.$_entity->parent_issue_id[0];
	
		$this->default_layouts->Draw_DivStart('block_area_2', '');
			$this->default_layouts->Draw_HyperLink($this->setUrl('admin'), '&larr; Administration', 'list_link_back');
			$this->default_layouts->Draw_HyperLink($this->setUrl('admin', $this->entity_name), '&larr; '.$this->chapter_name, 'list_link_back');
			$this->default_layouts->Draw_HyperLink($_par_link, '&larr; Parent', 'list_link_back');
		$this->default_layouts->Draw_DivEnd();
		
		Entity::inst()->init('div', 'class', 'block_area')->drawHeader();
		
		$this->initFormsLayouts();
		$this->form->formHeader( $this->setUrl('admin', $this->entity_name.'_'.$this->entity_actions['update'], $_id) );
		$this->form->formSubmit('Save');
		
		Entity::inst()->init('div', 'class', 'block_area_2')->drawHeader();
			$this->form->inputText('Name ', 30, $_entity->name[0], 'name');
			$this->form->inputText('Menu ', 30, $_entity->menu[0], 'menu');
			$this->form->inputText('Parent issue ', 30, $_entity->parent_issue_id[0], 'parent_issue_id');
		Entity::drawFooter_st('div', 'block_area_2');
		
		Entity::inst()->init('div', 'class', 'block_area_2')->drawHeader();
			$this->form->inputTextarea('Summary ', 80, 20, $_entity->summary[0], 'summary', 'edit_textfield');
			$this->form->inputTextarea('Description ', 80, 20, $_entity->description[0], 'description', 'edit_textfield_2');
			$this->form->inputTextarea('Additional description ', 80, 20, $_entity->description_2[0], 'description_2', 'edit_textfield_3');
		Entity::drawFooter_st('div', 'block_area_2');
		
		// Attachments
		// 1 array
		Entity::inst()->init('div', 'class', 'block_area_2')->drawHeader();
			Entity::inst()->init('h2')->setContent('Images array 1')->draw();
		
			$this->form->inputFile('Attchments ', 'img_arr[]', true);
			
			Entity::inst()->init('div', 'class', 'block_area_2')->drawHeader();
			$_len = count($_entity->img_arr_array[0]);
			for ($i = 0; $i < $_len; $i++) {
				Entity::initAndDrawHeader_st('div', 'class', 'img_arr_item');
					Entity::inst()->init('img', 'class', 'img_arr_img', 'self_closed')
						->addAttr('src', Settings::$path_to_attachments_dir.'issue/'.$_id.'/'.$_entity->img_arr_array[0][$i])
						->addAttr('width', '150px')
						->draw();
					
					$_drop_link = Entity::inst()->init('a', 'class', 'img_arr_drop_link')->addAttr('href', '?chapter=admin&action=issue_drop_img_arr&id='.$_id.'&sub_id='.$i)->addContent('drop')->toString();
						
					Entity::initAndAddContentAndDraw_st('div', 'class', 'drop_img_arr', 'closed', $_drop_link);
				Entity::drawFooter_st('div', 'img_arr_item');
			}
			Entity::drawFooter_st('div', 'block_area_2');
			
		Entity::drawFooter_st('div', 'block_area_2');
		
		// 2 array
		Entity::inst()->init('div', 'class', 'block_area_2')->drawHeader();
			Entity::inst()->init('h2')->setContent('Images array 2')->draw();
		
			$this->form->inputFile('Attchments ', 'img_arr_2[]', true);
			
			Entity::inst()->init('div', 'class', 'block_area_2')->drawHeader();
			$_len = count($_entity->img_arr_array_2[0]);
			for ($i = 0; $i < $_len; $i++) {
				Entity::initAndDrawHeader_st('div', 'class', 'img_arr_item');
					Entity::inst()->init('img', 'class', 'img_arr_img', 'self_closed')
						->addAttr('src', Settings::$path_to_attachments_dir.'issue/'.$_id.'/img_arr_2/'.$_entity->img_arr_array_2[0][$i])
						->addAttr('width', '150px')
						->draw();
					
					$_drop_link = Entity::inst()->init('a', 'class', 'img_arr_drop_link')->addAttr('href', '?chapter=admin&action=issue_drop_img_arr_2&id='.$_id.'&sub_id='.$i)->addContent('drop')->toString();
						
					Entity::initAndAddContentAndDraw_st('div', 'class', 'drop_img_arr', 'closed', $_drop_link);
				Entity::drawFooter_st('div', 'img_arr_item');
			}
			Entity::drawFooter_st('div', 'block_area_2');
		Entity::drawFooter_st('div', 'block_area_2');
		
		// Properties
		Entity::inst()->init('div', 'class', 'block_area_2')->drawHeader();
		if( isset($_entity->entity_properties_table) ){
			Entity::inst()->init('h2')->setContent('Properties')->draw();
			$_properties_table = $_entity->properties_table;
			for ($i = 0; $i < $_properties_table->len; $i++) {
				if($_properties_table->type[$i] == 'text'){
					$_current_val = $_entity->getPropertyValue($_entity->id[0], $_properties_table->id[$i]);
					$this->form->inputText($_properties_table->name[$i].' ', 30, $_current_val, $_properties_table->field_name[$i]);
				}
			}
		}
		Entity::drawFooter_st('div', 'block_area_2');
		
		$this->form->formSubmit('Save');
		$this->form->formFooter();
		
		Entity::drawFooter_st('div', 'block_area');
		
	/*
		$this->initFormsLayouts();
		$this->form->formHeader( $this->setUrl('111111admin', $this->entity_name.'_'.$this->entity_actions['update'], $_entity->id[0]) );
		$this->form->formSubmit('Save');
	
		$_len = count($_entity->entity_sheme_names);
		for ($i = 0; $i < $_len; $i++) {
			if($_entity->entity_sheme_form[$i] == 'text'){
				$name = $this->getViriableValueByName($_entity, $_entity->entity_sheme_names[$i]);
				$this->form->inputText($_entity->entity_sheme_descr[$i].' ', 30, $name, $_entity->entity_sheme_names[$i]);
			}
			else if($_entity->entity_sheme_form[$i] == 'textarea'){
				$name = $this->getViriableValueByName($_entity, $_entity->entity_sheme_names[$i]);
				$this->form->inputTextarea($_entity->entity_sheme_descr[$i], 80, 10, $name, $_entity->entity_sheme_names[$i]);
			}
			else if($_entity->entity_sheme_form[$i] == 'select'){
				$_field_name = $_entity->entity_sheme_names[$i];
				$_depending_variable = $_entity->entity_depending[ $_field_name ];
				$menu = $this->getViriableValueByName($_entity, $_entity->entity_sheme_names[$i]);
				$this->form->inputSelect($_entity->entity_sheme_descr[$i], $_entity->entity_sheme_names[$i], $_entity->$_depending_variable->id, $menu, $_entity->$_depending_variable->name);
			}
		}
	
		if(isset($_entity->entity_properties_table)){
			$this->default_layouts->Draw_H2('Properties');
			$_properties_table = $_entity->properties_table;
			for ($i = 0; $i < $_properties_table->len; $i++) {
				if($_properties_table->type[$i] == 'text'){
					$_current_val = $_entity->getPropertyValue($_entity->id[0], $_properties_table->id[$i]);
					//$_entity->properties_table_data[$_entity->id[0]]->data_value[$_properties_table->id[$i]];
					$this->form->inputText($_properties_table->name[$i].' ', 30, $_current_val, $_properties_table->field_name[$i]);
				}
			}
		}
	
		$this->form->formSubmit('Save');
		$this->form->formFooter();*/
	//}
}