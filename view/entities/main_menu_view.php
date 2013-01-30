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
}