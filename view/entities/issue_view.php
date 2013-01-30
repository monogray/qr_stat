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
	
		$this->processListByMenu_header();
	
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
	
	protected function processListByMenu_header() {
		$this->default_layouts->Draw_H1($this->chapter_name.' list');
	
		$this->default_layouts->Draw_DivStart('block_area_2', '');
			$this->default_layouts->Draw_HyperLink($this->setUrl('admin'), '&larr; Administration', 'list_link_back');
			$this->default_layouts->Draw_HyperLink($this->setUrl('admin', 'main_menu'), '&larr; Main menu', 'list_link_back');
		$this->default_layouts->Draw_DivEnd();
	
		$this->default_layouts->Draw_DivStart('', '');
			$this->default_layouts->Draw_HyperLink($this->setUrl('admin', $this->entity_name.'_'.$this->entity_actions['create']), '+ New', 'list_link');
		$this->default_layouts->Draw_DivEnd();
	}
}