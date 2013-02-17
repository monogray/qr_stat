<?php 
include_once 'view_entities.php';
class IssueView extends ViewEntities {
	protected $entity_name			= 'issue';
	protected $chapter_name			= 'Issue';
	
	protected $entity_actions		= Array('list'				=> 'list',
											'view'				=> 'view',
											'create'			=> 'create',
											'drop'				=> 'drop',
											'update'			=> 'update',
											'list_by_menu'		=> 'list_by_menu',
											'create_by_menu'	=> 'create_by_menu',
											'create_by_issue'	=> 'create_by_issue',
											'list_by_issue'		=> 'list_by_issue');
	
	function IssueView($_app_state) {
		parent::ViewEntities();
		
		$this->setAppState($_app_state);
		$this->setTemplate('default');
	}
	
	public function process() {
		parent::process();
		if($this->app_state->getActionInner() == $this->entity_actions['list_by_menu']){
			$this->processListByMenu();
		}else if($this->app_state->getActionInner() == $this->entity_actions['create_by_menu']){
			$this->processView();
		}else if($this->app_state->getActionInner() == $this->entity_actions['list_by_issue']){
			$this->processListByMenu();
		}else if($this->app_state->getActionInner() == $this->entity_actions['create_by_issue']){
			$this->processView();
		}
	}
	
	protected function processListByMenu() {
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
	
		Entity::inst()->init('h1')->setContent($this->chapter_name.' view. (id '.$_entity->id[0].')')->draw();
	
		$this->default_layouts->Draw_DivStart('block_area_2', '');
			$this->default_layouts->Draw_HyperLink($this->setUrl('admin'), '&larr; Administration', 'list_link_back');
			$this->default_layouts->Draw_HyperLink($this->setUrl('admin', $this->entity_name), '&larr; '.$this->chapter_name, 'list_link_back');
		$this->default_layouts->Draw_DivEnd();
		
		Entity::inst()->init('div', 'class', 'block_area')->drawHeader();
		
		$this->initFormsLayouts();
		$this->form->formHeader( $this->setUrl('admin', $this->entity_name.'_'.$this->entity_actions['update'], $_entity->id[0]) );
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
		
		Entity::inst()->init('div', 'class', 'block_area_2')->drawHeader();
			$this->form->inputFile('Attchments ', 'img_arr[]', true);
			
			Entity::inst()->init('div', 'class', 'block_area_2')->drawHeader();
			$_len = count($_entity->img_arr_array[0]);
			for ($i = 0; $i < $_len; $i++) {
				Entity::inst()->init('img', 'class', 'img_arr_img', 'self_closed')
					->addAttr('src', Settings::$path_to_attachments_dir.'issue/'.$_entity->id[0].'/'.$_entity->img_arr_array[0][$i])
					->addAttr('width', '150px')
					->draw();
			}
			Entity::drawFooter_st('div', 'block_area_2');
			
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
	}
}