<?php 
include_once 'view_entities.php';
class MainMenuView extends ViewEntities {
	protected $entity_name			= 'main_menu';
	protected $chapter_name			= 'Main menu';
	
	protected $entity_actions		= Array('list'			=> 'list',
											'view'			=> 'view',
											'create'		=> 'create',
											'drop'			=> 'drop',
											'update'		=> 'update',
											'add_issue'		=> 'create_by_menu',
											'view_issues'	=> 'list_by_menu');
	
	function MainMenuView($_app_state) {
		parent::ViewEntities();
		
		$this->setAppState($_app_state);
		$this->setTemplate('default');
	}
	
	public function process() {
		parent::process();
	}
	
	protected function processList() {
		$_entity = $this->getAppData();
	
		$this->processList_header();
	
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
				$this->default_layouts->Draw_HyperLink($this->setUrl('admin', 'issue_'.$this->entity_actions['view_issues'], $_entity->id[$i]), 'View', 'list_link');
			echo '</td>';
			
			echo '<td>';
				$this->default_layouts->Draw_HyperLink($this->setUrl('admin', 'issue_'.$this->entity_actions['add_issue'], $_entity->id[$i]), 'Add', 'list_link');
			echo '</td>';
			
			echo '<td>';
				$this->default_layouts->Draw_HyperLink($this->setUrl('admin', $this->entity_name.'_'.$this->entity_actions['drop'], $_entity->id[$i]), 'Drop', 'list_link');
			echo '</td>';
				
			echo '</tr>';
		}
		echo '<table>';
	}
	
	protected function processView() {
		$_entity = $this->getAppData();
	
		$this->default_layouts->Draw_H1($this->chapter_name.' view. (id '.$_entity->id[0].')');
	
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
			$this->form->inputText('Chapter ', 30, $_entity->chapter[0], 'chapter');
		Entity::drawFooter_st('div', 'block_area_2');
		
		Entity::inst()->init('div', 'class', 'block_area_2')->drawHeader();
			$this->form->inputTextarea('Description ', 80, 16, $_entity->description[0], 'description', 'edit_textfield');
		Entity::drawFooter_st('div', 'block_area_2');
		
		Entity::inst()->init('div', 'class', 'block_area_2')->drawHeader();
			$this->form->inputText('Meta Keywords ', 30, $_entity->meta_keywords[0], 'meta_keywords');
			$this->form->inputText('Meta Description ', 30, $_entity->meta_description[0], 'meta_description');
			$this->form->inputText('HTML Title ', 30, $_entity->html_title[0], 'html_title');
		Entity::drawFooter_st('div', 'block_area_2');
		
		Entity::inst()->init('div', 'class', 'block_area_2')->drawHeader();
			$this->form->inputText('img_1 ', 30, $_entity->img_1[0], 'img_1');
			$this->form->inputText('img_2 ', 30, $_entity->img_2[0], 'img_2');
		Entity::drawFooter_st('div', 'block_area_2');
	
		/* ???????????????
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
		}*/
		
		$this->form->formSubmit('Save');
		$this->form->formFooter();
		
		Entity::drawFooter_st('div', 'block_area');
	}
}