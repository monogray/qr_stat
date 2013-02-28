<?php
include_once 'view_entities_core.php';
class ViewEntities extends ViewEntitiesCore{
	protected $default_layouts;
	
	protected $entity_actions		= Array('list'		=> 'list',
											'view'		=> 'view',
											'create'	=> 'create',
											'drop'		=> 'drop',
											'update'	=> 'update');
	
	protected $chapter_name			= '';

	function ViewEntities(){
		$this->setTemplate('default');
		
		require_once 'layouts/mono_layouts.php';
		$this->default_layouts = new MonoLayouts();
		
		include_once 'layouts/html_entity/entity.php';
		include_once 'layouts/html_entity/div.php';
	}
	
	// Owerride
	protected function drawInHeader() {
		echo'<!-- TinyMCE -->
			<script type="text/javascript" src="public/js/tiny_mce/tiny_mce.js"></script>
			<script type="text/javascript" src="public/js/adminka/tiny_mce_processing.js"></script>
			<script type="text/javascript">
				initTinyMCE();
			</script>
	
			<!-- jQuery UI -->
			<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js" type="text/javascript" charset="utf-8"></script>
			<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" type="text/css" media="screen" charset="utf-8">';
	}
	
	public function draw() {
		$this->drawHeader();
			$this->drawTop();
			$this->drawInfoMessage();
				$this->process();
			$this->drawFooter();
		$this->drawEnd();
	}
	
	protected function drawInfoMessage() {
		if($_SESSION['message'] != null && $_SESSION['message'] != ''){
			$this->default_layouts->Draw_DivStart('', 'info_message_container');
			echo $_SESSION['message'];
			$this->default_layouts->Draw_DivEnd();
			
			echo'<script>
					$(document).ready(function() {
						$("#info_message_container").hide(0).show(800);
						setTimeout(timeOut, 8000);
					
						$("#info_message_container").click(function() {
							timeOut();
						});
					});
					
					function timeOut() {
						$("#info_message_container").hide(500);
					}
			</script>';
			$_SESSION['message'] = '';
		}
	}
	
	public function process() {
		if( $this->actionInnerMathWith($this->entity_name) ){
			$this->processIndex();
		}
		else if( $this->actionInnerMathWith($this->entity_actions['list']) ){
			$this->processList();
		}
		else if( $this->actionInnerMathWith($this->entity_actions['view']) ){
			$this->processView();
		}
		else if( $this->actionInnerMathWith($this->entity_actions['create']) ){
			$this->processList();
		}
		else if( $this->actionInnerMathWith($this->entity_actions['drop']) ){
			$this->processList();
		}
		else if( $this->actionInnerMathWith($this->entity_actions['update']) ){
			$this->processView();
		}
	}
	
	protected function processIndex() {
		$this->processList();
	}
	
	protected function processList() {
		$_entity = $this->getAppData();
		
		$this->processList_header();
		
		echo '<table class="list_table">
				<tr><td>#</td><td>Id</td><td>Name</td><td>Date</td><td>Drop</td></tr>';
		for ($i = 0; $i < $_entity->len; $i++) {
			echo '<tr>';
			echo '<td>'.($i+1).'</td>';
			echo '<td>'.$_entity->id[$i].'</td>';
			echo '<td>';
			$this->default_layouts->Draw_HyperLink($this->setUrl('admin', $this->entity_name.'_'.$this->entity_actions['view'], $_entity->id[$i]), $_entity->name[$i], 'list_link');
			echo '</td>';
			
			echo '<td>'.$_entity->date[$i].'</td>';
			
			echo '<td>';
				$this->default_layouts->Draw_HyperLink($this->setUrl('admin', $this->entity_name.'_'.$this->entity_actions['drop'], $_entity->id[$i]), 'Drop', 'list_link');
			echo '</td>';
			
			echo '</tr>';
		}
		
		echo '<table>';
	}
	
	protected function processList_header() {
		$this->default_layouts->Draw_H1($this->chapter_name.' list');
		
		$this->default_layouts->Draw_DivStart('block_area_2', '');
			$this->default_layouts->Draw_HyperLink($this->setUrl('admin'), '&larr; Administration', 'list_link_back');
		$this->default_layouts->Draw_DivEnd();
		
		$this->default_layouts->Draw_DivStart('', '');
			$this->default_layouts->Draw_HyperLink($this->setUrl('admin', $this->entity_name.'_'.$this->entity_actions['create']), '+ New', 'list_link');
		$this->default_layouts->Draw_DivEnd();
	}
	
	protected function processView() {
		$_entity = $this->getAppData();
		
		$this->default_layouts->Draw_H1($this->chapter_name.' view. (id '.$_entity->id[0].')');
		
		$this->default_layouts->Draw_DivStart('block_area_2', '');
			$this->default_layouts->Draw_HyperLink($this->setUrl('admin'), '&larr; Administration', 'list_link_back');
			$this->default_layouts->Draw_HyperLink($this->setUrl('admin', $this->entity_name), '&larr; '.$this->chapter_name, 'list_link_back');
		$this->default_layouts->Draw_DivEnd();
		
		$this->initFormsLayouts();
		$this->form->formHeader( $this->setUrl('admin', $this->entity_name.'_'.$this->entity_actions['update'], $_entity->id[0]) );
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
		$this->form->formFooter();
	}
	
	protected function getViriableValueByName($_entity, $_var_name) {
		$var_name = $_var_name;
		$name = $_entity->$var_name;
		$name = $name[0];
		return $name;
	}
}