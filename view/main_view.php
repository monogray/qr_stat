<?php
include_once 'view_core.php';
class MainView extends View_Core{
	private $html_elements;
	private $forms_layouts;

	function MainView($_db_connect) {
		parent::View_Core($_db_connect);
		
		$this->setTemplate('default');
		
		require_once 'layouts/mono_layouts.php';
		$this->html_elements = new MonoLayouts();
	}
	
	function initFormsLayouts() {
		require_once 'layouts/form_layouts.php';
		$this->forms_layouts = new FormsLayout();
	}
	
	public function draw($_func) {
		$this->drawHeader();
			$this->drawTop();
			$this->drawInfoMessage();
				call_user_func( array($this, $_func) );
			$this->drawFooter();
		$this->drawEnd();
	}
	
	public function drawInfoMessage() {
		if($_SESSION['message'] != null && $_SESSION['message'] != ''){
			$this->html_elements->Draw_DivStart('', 'info_message_container');
				echo $_SESSION['message'];
			$this->html_elements->Draw_DivEnd();
			echo'<script>
					$(document).ready(function(){
						$("#info_message_container").hide(0).show(800);
						setTimeout(timeOut, 8000);
					
						$("#info_message_container").click(function() {
							timeOut();
						});
					});
					
					function timeOut(){
						$("#info_message_container").hide(500);
					}
			</script>';
			$_SESSION['message'] = '';
		}
	}
	
	public function draw_IndexPage() {
		$this->html_elements->Draw_DivStart('', '');
		$this->html_elements->Draw_HyperLink($this->setUrl('admin'), 'Administration', 'list_link');
		$this->html_elements->Draw_DivEnd();
	}
	
	public function draw_Admin_IndexPage() {
		$this->html_elements->Draw_H1('Administration');
		
		$this->html_elements->Draw_DivStart('block_area_2');
			$this->html_elements->Draw_HyperLink($this->setUrl('index'), '&larr; Home', 'list_link');
		$this->html_elements->Draw_DivEnd();
		
		$this->html_elements->Draw_DivStart('block_area');
			$this->html_elements->Draw_H2('Guests');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'guests_list'), 'Guests List', 'list_link');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'events_list'), 'Events', 'list_link');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'groups_list'), 'Groups', 'list_link');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'statistics'), 'Statistics', 'list_link');
		$this->html_elements->Draw_DivEnd();
		
		$this->html_elements->Draw_DivStart('block_area');
			$this->html_elements->Draw_H2('Site');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'main_menu'), 'Main menu', 'list_link');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'issue'), 'Issues', 'list_link');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'issue_properties'), 'Issues properties', 'list_link');
		$this->html_elements->Draw_DivEnd();
	}
	
	
	// ______________________________________________________________________________________________________________________
	// qr_manager_guests
	
	public function draw_Admin_GuestsList() {
		// Package form processing javascript
		echo'<script type="text/javascript" src="public/js/adminka/draw_Admin_GuestsList.js"></script>';
		
		$_entity = $this->getAppData()->get_QR_manager_guest();
		$_groups = $this->getAppData()->get_QR_manager_guest_groups();
		
		$this->html_elements->Draw_H1('Guests List');
		
		$this->html_elements->Draw_DivStart('', '');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin'), '&larr; Administration', 'list_link');
		$this->html_elements->Draw_DivEnd();
		
		$this->html_elements->Draw_DivStart('block_area_2', '');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'guests_create'), '+ New', 'list_link');
		$this->html_elements->Draw_DivEnd();
		
		$this->html_elements->Draw_DivStart('block_area_2', '');
			$this->html_elements->Draw_H2('Package actions +', '', 'package_action_header');
			
			// Package form
			$this->initFormsLayouts();
			
			$this->forms_layouts->formHeader( '', 'guests_pack_processing' );
			$this->html_elements->Draw_DivStart('', 'package_action_content');
				
				$this->html_elements->Draw_DivStart('block_area', '');
				$this->forms_layouts->inputText('Select items: ', 30, '', 'package_select', '');
				echo '<div id="package_select_bt">Select</div>';
				$this->html_elements->Draw_DivEnd();
				
				$this->html_elements->Draw_DivStart('block_area', '');
				$this->forms_layouts->inputText('Count: ', 30, '', 'package_add', '');
				echo '<div id="package_add_bt">Add</div>';
				$this->html_elements->Draw_DivEnd();
				
				$this->html_elements->Draw_DivStart('block_area', '');
				$this->forms_layouts->inputSelect('Move to group', 'move_to_group', $_groups->id, '', $_groups->name);
				echo '<div id="guests_pack_move_to_grop">Go</div>';
				$this->html_elements->Draw_DivEnd();
				
				$this->html_elements->Draw_DivStart('block_area', '');
				echo '<div id="guests_pack_drop">Drop selected</div>';
				$this->html_elements->Draw_DivEnd();
				
				$this->html_elements->Draw_DivStart('block_area', '');
				echo '<div id="guests_pack_normalize_qr">Normalize QR</div>';
				$this->html_elements->Draw_DivEnd();
				
			$this->html_elements->Draw_DivEnd();	//package_action_content
		$this->html_elements->Draw_DivEnd();
		
		echo '<table class="list_table">
				<tr><td></td><td>#</td><td>Id</td><td>QR</td><td>Name</td><td>Group</td><td>Date</td><td>Drop</td></tr>';
		
		for ($i = 0; $i < $_entity->len; $i++) {
			echo '<tr>';
			
			echo '<td>';
				$this->forms_layouts->inputCheckbox('', 'guests_pack[]', $_entity->id[$i], '', 0);
			echo '</td>';
			
			
			echo '<td>'.($i+1).'</td>';
			
			echo '<td>';
				$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'guests_view', $_entity->id[$i]), $_entity->id[$i], 'list_link');
			echo '</td>';
			
			echo '<td>'.$_entity->qr[$i].'</td>';
			
			echo '<td>';
				$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'guests_view', $_entity->id[$i]), $_entity->name[$i], 'list_link');
			echo '</td>';
			
			echo '<td>';
				$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'groups_view', $_entity->groups_id[$i]), $_entity->groups_name[$i], 'list_link');
			echo '</td>';
			
			echo '<td>'.
				date("d.m.Y H:i", strtotime( $_entity->date[$i] ) ).
			'</td>';
			
			echo '<td>';
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'guests_drop', $_entity->id[$i]), 'Drop', 'list_link');
			echo '</td>';
			
			echo '</tr>';
		}
		echo '</table>';
		$this->forms_layouts->formFooter();
	}
	
	public function draw_Admin_GuestsView() {
		$this->initFormsLayouts();
		$_tab = $this->getAppData()->get_QR_manager_guest();
		
		$_id = $_tab->id[0];
		$_name = $_tab->name[0];
		$_surname = $_tab->surname[0];
		$_patronymic = $_tab->patronymic[0];
		$_groups_id = $_tab->groups_id[0];
		$_qr = $_tab->qr[0];
		$_groups_name = $_tab->groups_name[0];
		$_date = $_tab->date[0];
		
		$_events_activity_id = $_tab->events_activity_id;
		$_events_activity_name = $_tab->events_activity_name;
		$_events_activity_val = $_tab->events_activity_val;
		$_events_activity_len = $_tab->events_activity_len;
		
		$this->html_elements->Draw_H1('id '.$_id.'. '.$_surname.' '.$_name.' '.$_patronymic);
		
		$this->draw_BackLinks_for_Admin_GuestsInfoPreview();
		
		$this->forms_layouts->formHeader( $this->setUrl('admin', 'guests_save', $_id) );
		
		$this->forms_layouts->formSubmit('Save');
		
		$this->html_elements->Draw_DivStart('block_area');
			$this->html_elements->Draw_H2('Info');
			$this->forms_layouts->inputText('Name ', 30, $_name, 'name');
			$this->forms_layouts->inputText('Surname ', 30, $_surname, 'surname');
			$this->forms_layouts->inputText('Patronymic ', 30, $_patronymic, 'patronymic');
		$this->html_elements->Draw_DivEnd();
		
		$this->html_elements->Draw_DivStart('block_area');
			$this->html_elements->Draw_H2('QR Code');
			$this->html_elements->Draw_image('http://qrfree.kaywa.com/?s=8&d='.$_qr, '', '', '', '80px');
			$this->forms_layouts->inputText('QR: ', 30, $_qr, 'qr');
		$this->html_elements->Draw_DivEnd();
		
		// Groups
		$this->html_elements->Draw_DivStart('block_area');
		$this->html_elements->Draw_H2('Groups');
		$this->forms_layouts->inputSelect( 'Group: ', 'groups', $_tab->getAllGroupsIdList(),$_groups_id, $_tab->getAllGroupsNameList() );
		$this->html_elements->Draw_DivEnd();
		
		// Events activity
		$this->html_elements->Draw_DivStart('block_area');
		$this->html_elements->Draw_H2('Events activity');
		for ($i = 0; $i < $_events_activity_len; $i++) {
			if(!isset($_events_activity_val[$i]) || $_events_activity_val[$i] == ''){
				$_events_activity_val[$i] = 0;
			}
			$this->forms_layouts->inputCheckbox($_events_activity_name[$i], 'events_activity[]', $_events_activity_id[$i], '', $_events_activity_val[$i]);
		}
		
		$this->html_elements->Draw_DivEnd();
		
		$this->html_elements->Draw_DivStart('block_area');
		$this->html_elements->Draw_H2('Date');
		echo $_date;
		$this->html_elements->Draw_DivEnd();
		
		$this->html_elements->Draw_DivStart('block_area');
		$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'guests_info_preview', $_id), 'View info page preview', 'list_link', '', '', '_blank');
		$this->html_elements->Draw_DivEnd();
			
		$this->forms_layouts->formSubmit('Save');
		$this->forms_layouts->formFooter();
		
		$this->draw_BackLinks_for_Admin_GuestsInfoPreview();
	}
	
	public function draw_BackLinks_for_Admin_GuestsInfoPreview() {
		$this->html_elements->Draw_DivStart('block_area_2');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin'), '&larr; Administration', 'list_link_back');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'guests_list'), '&larr; Guests List', 'list_link_back');
		$this->html_elements->Draw_DivEnd();
	}
	
	// Page for flash app. Display User's group info
	public function draw_Admin_GuestsInfoPreview() {
		$_tab = $this->getAppData()->get_QR_manager_guest();
		
		$_url = explode("/", $_SERVER["PHP_SELF"]);
		$_url_final = 'http://'.$_SERVER["HTTP_HOST"].'/'.$_url[1].'/'.$_tab->groups->img_arr_path[0].$_tab->groups->img_arr[0][0];
		$this->html_elements->Draw_image($_url_final, '', 'group_img');
		
		$this->html_elements->Draw_DivStart('top_text');
		$this->html_elements->Draw_H1($_tab->surname[0].' '.$_tab->name[0].' '.$_tab->patronymic[0]);
		$this->html_elements->Draw_H2($_tab->groups_name[0]);
		$this->html_elements->Draw_DivEnd();
	}
	
	// Page for flash app. Display result of user's registration on event
	public function draw_Admin_GuestsRegisterEvent() {
		$_tab = $this->getAppData()->get_QR_manager_guest();
	
		$_url = explode("/", $_SERVER["PHP_SELF"]);
		$_url_final = 'http://'.$_SERVER["HTTP_HOST"].'/'.$_url[1].'/'.$_tab->groups->img_arr_path[0].$_tab->groups->img_arr[0][0];
		$this->html_elements->Draw_image($_url_final, '', 'group_img');
	
		$this->html_elements->Draw_DivStart('top_text');
		$this->html_elements->Draw_H1($_tab->surname[0].' '.$_tab->name[0].' '.$_tab->patronymic[0]);
		$this->html_elements->Draw_H2($_tab->groups_name[0]);
		
		echo 'Успешно зарегистрирован на мероприятии '.$_tab->reg_type->name_by_id[ $_GET['sub_id'] ];
		
		$this->html_elements->Draw_DivEnd();
	}
	
	// END qr_manager_guests
	// ______________________________________________________________________________________________________________________
	
	
	
	
	// ______________________________________________________________________________________________________________________
	// qr_manager_guests_reg_types
	
	public function draw_Admin_EventsList() {
		$_entity = $this->getAppData()->get_QR_manager_guest_reg_types();
		
		$this->html_elements->Draw_H1('Events List');
		
		$this->html_elements->Draw_DivStart('block_area_2', '');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin'), '&larr; Administration', 'list_link_back');
		$this->html_elements->Draw_DivEnd();
		
		$this->html_elements->Draw_DivStart('', '');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'events_create'), '+ New', 'list_link');
		$this->html_elements->Draw_DivEnd();
		
		echo '<table class="list_table">
				<tr><td>#</td><td>Id</td><td>Name</td><td>Date</td><td>Drop</td></tr>';
		for ($i = 0; $i < $_entity->len; $i++) {
			echo '<tr>';
			echo '<td>'.$i.'</td>';
			echo '<td>'.$_entity->id[$i].'</td>';
			//echo '<td>'.$_entity->name[$i].'</td>';
			echo '<td>';
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'events_view', $_entity->id[$i]), $_entity->name[$i], 'list_link');
			echo '</td>';
			
			echo '<td>'.$_entity->date[$i].'</td>';
			
			echo '<td>';
				//$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'events_drop_confirm', $_entity->id[$i]), 'Drop', 'list_link');
				$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'events_drop', $_entity->id[$i]), 'Drop', 'list_link');
			echo '</td>';
			
			echo '</tr>';
		}
		
		echo '<table>';
	}
	
	public function draw_Admin_EventsView() {
		$this->initFormsLayouts();
		$_data = $this->getAppData()->getData();
		
		$this->html_elements->Draw_H1($_data[0]['name']);
		
		$this->html_elements->Draw_DivStart('block_area_2', '');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin'), '&larr; Administration', 'list_link_back');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'events_list'), '&larr; Events List', 'list_link_back');
		$this->html_elements->Draw_DivEnd();
		
		$this->forms_layouts->formHeader( $this->setUrl('admin', 'events_save', $_data[0]['id']) );
			$this->html_elements->Draw_DivStart('block_area', '');
				$this->forms_layouts->inputText('Name ', 30, $_data[0]['name'], 'name');
				$this->forms_layouts->inputTextarea('Description ', 80, 10, $_data[0]['description'], 'description');
				$this->html_elements->Draw_DivStart('', '');
					echo 'Date: '. $_data[0]['date'];
				$this->html_elements->Draw_DivEnd();
			$this->html_elements->Draw_DivEnd();
				
			$this->forms_layouts->formSubmit('Save');
		$this->forms_layouts->formFooter();
	}
	
	// END qr_manager_guests_reg_types
	// ______________________________________________________________________________________________________________________
	
	
	
	
	
	// ______________________________________________________________________________________________________________________
	// qr_manager_guests_groups
	
	public function draw_Admin_GroupsList() {
		$this->html_elements->Draw_H1('Groups List');
	
		$this->html_elements->Draw_DivStart('', '');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin'), '&larr; Administration', 'list_link');
		$this->html_elements->Draw_DivEnd();
	
		$this->html_elements->Draw_DivStart('block_area_2', '');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'groups_create'), '+ New', 'list_link');
		$this->html_elements->Draw_DivEnd();
		
		$this->html_elements->Draw_DivStart('block_area_2', '');
		$this->html_elements->Draw_H2('More options');
		
		// Create many
		$this->initFormsLayouts();
		$this->html_elements->Draw_DivStart('block_area', '');
			$this->forms_layouts->formHeader( $this->setUrl('admin', 'groups_create_many') );
			$this->forms_layouts->inputText('Count ', 20, '', 'count');
			$this->forms_layouts->formSubmit('Create');
			$this->forms_layouts->formFooter();
		$this->html_elements->Draw_DivEnd();
		
		$this->html_elements->Draw_DivEnd();
	
		$_entity = $this->getAppData()->get_QR_manager_guest_groups();
	
		echo '<table class="list_table">
				<tr><td>#</td><td>Id</td><td>Name</td><td>Date</td><td>Drop</td></tr>';
		
		for ($i = 0; $i < $_entity->len; $i++) {
			echo '<tr>';
				echo '<td>'.$i.'</td>';
				echo '<td>';
					$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'groups_view', $_entity->id[$i]), $_entity->id[$i], 'list_link');
				echo '</td>';
				echo '<td>';
					$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'groups_view', $_entity->id[$i]), $_entity->name[$i], 'list_link');
				echo '</td>';
				echo '<td>'.
					date("d.m.Y H:i", strtotime( $_entity->date[$i] ) ).
				'</td>';
				echo '<td>';
					$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'groups_drop_confirm', $_entity->id[$i]), 'Drop', 'list_link');
				echo '</td>';
			echo '</tr>';
		}
		echo '</table>';
		
		$this->html_elements->Draw_DivStart('block_area_2', '');
		$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'groups_create'), '+ New', 'list_link');
		$this->html_elements->Draw_DivEnd();
	}
	
	public function draw_Admin_GroupsView() {
		$this->initFormsLayouts();
		$_entity = $this->getAppData()->get_QR_manager_guest_groups();

		$this->html_elements->Draw_H1($_entity->name[0]);
		
		$this->html_elements->Draw_DivStart('block_area_2', '');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin'), '&larr; Administration', 'list_link_back');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'groups_list'), '&larr; Groups List', 'list_link_back');
		$this->html_elements->Draw_DivEnd();
		
		$this->html_elements->Draw_DivStart('block_area', '');
			$this->html_elements->Draw_H2('Groups info');
			$this->forms_layouts->formHeader( $this->setUrl('admin', 'groups_save', $_entity->id[0]) );
			$this->forms_layouts->inputText('Name ', 30, $_entity->name[0], 'name');
			$this->forms_layouts->inputTextarea('Description ', 80, 10, $_entity->description[0], 'description');
			$this->forms_layouts->inputTextarea('Description ', 80, 10, $_entity->description_2[0], 'description_2');
		$this->html_elements->Draw_DivEnd();
		
		// Img Arr
		$this->html_elements->Draw_DivStart('block_area', '');
			$this->html_elements->Draw_H2('Images array');
			$this->forms_layouts->inputFile('Images ', 'img_arr[]', true);
			for ($i = 0; $i < $_entity->img_arr_len[0]; $i++) {
				$this->html_elements->Draw_DivStart('block_area', '');
				echo '<a href="'.$_entity->img_arr_path[0].$_entity->img_arr[0][$i].'" class="list_link" target="_blank">'.($i+1).'. '.$_entity->img_arr[0][$i].'</a>';
				echo '<img src="'.$_entity->img_arr_path[0].$_entity->img_arr[0][$i].'" height="100px"/>';
				$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'groups_drop_img_arr', $_entity->id[0], (string)$i), 'Drop', 'list_link');
				$this->html_elements->Draw_DivEnd();
			}
		$this->html_elements->Draw_DivEnd();
		
		$this->html_elements->Draw_DivStart('block_area', '');
			$this->html_elements->Draw_H2('Date');
			echo $_entity->date[0];
		$this->html_elements->Draw_DivEnd();
		
		$this->html_elements->Draw_DivStart('block_area', '');
			$this->forms_layouts->formSubmit('Save');
		$this->html_elements->Draw_DivEnd();
		
		$this->forms_layouts->formFooter();
	}
	
	public function draw_Admin_GroupsDropConfirm() {
		$this->initFormsLayouts();
		$_entity = $this->getAppData()->get_QR_manager_guest_groups();
	
		$this->html_elements->Draw_H1('Delete groupe "'.$_entity->name[0].'"?');
	
		$this->html_elements->Draw_DivStart('block_area_2', '');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin'), '&larr; Administration', 'list_link_back');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'groups_list'), '&larr; Groups List', 'list_link_back');
		$this->html_elements->Draw_DivEnd();
	
		$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'groups_drop', $_entity->id[0]), 'Yes', 'list_link');
		$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'groups_list'), 'No', 'list_link');
			
		$this->forms_layouts->formSubmit('Save');
		$this->forms_layouts->formFooter();
	}
	
	// END qr_manager_guests_groups
	// ______________________________________________________________________________________________________________________
	
	
	
	
	// ______________________________________________________________________________________________________________________
	// Statistics
	
	function data_Admin_Statistics() {
		$this->html_elements->Draw_H1('Statistics');
		
		$this->html_elements->Draw_DivStart('block_area_2', '');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin'), '&larr; Administration', 'list_link_back');
		$this->html_elements->Draw_DivEnd();
		
		$this->html_elements->Draw_DivStart('', '');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'statistics_by_events'), 'Statistics by events', 'list_link');
		$this->html_elements->Draw_DivEnd();
	}
	
	function data_Admin_StatisticsByEvents() {
		$this->html_elements->Draw_H1('Statistics by events');
	
		$this->html_elements->Draw_DivStart('block_area_2', '');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin'), '&larr; Administration', 'list_link_back');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'statistics'), '&larr; Statistics', 'list_link_back');
		$this->html_elements->Draw_DivEnd();
	
		$_entity = $this->getAppData()->get_QR_manager_guest_reg_types();
		
		echo '<table class="list_table">
				<tr><td>#</td><td>Event id</td><td>Event name</td><td>Counts by time</td><td>Guests list</td></tr>';
		for ($i = 0; $i < $_entity->len; $i++) {
			echo '<tr>';
			echo '<td>'.($i+1).'</td>';
			echo '<td>'.$_entity->id[$i].'</td>';
			echo '<td>';
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'events_view', $_entity->id[$i]), $_entity->name[$i], 'list_link');
			echo '</td>';
			
			echo '<td>';
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'statistics_by_events_view', $_entity->id[$i]), 'View', 'list_link');
			echo '</td>';
			
			echo '<td>';
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'statistics_by_events_view_guests', $_entity->id[$i]), 'View', 'list_link');
			echo '</td>';
		
			echo '</tr>';
		}
		
		echo '<table>';
	}
	
	function data_Admin_StatisticsByEventsView() {
		$_entity = $this->getAppData()->get_QR_manager_events_statistics();
		
		$this->html_elements->Draw_H1('Statistics by events view. Event "'.$_entity->events->name[0].'" [id '.$_entity->events->id[0].']');
		
		$this->html_elements->Draw_DivStart('block_area_2', '');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin'), '&larr; Administration', 'list_link_back');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'statistics'), '&larr; Statistics', 'list_link_back');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'statistics_by_events'), '&larr; Statistics by event', 'list_link_back');
		$this->html_elements->Draw_DivEnd();
		
		echo '<table class="list_table">
				<tr><td>#</td><td>Date</td><td>Count</td></tr>';
		for ($i = 0; $i < $_entity->len; $i++) {
			echo '<tr>';
			echo '<td>'.($i+1).'</td>';
			echo '<td>'.$_entity->dates[$i].'</td>';
			
			echo '<td>'.$_entity->dates_count[$i].'</td>';
		
			echo '</tr>';
		}
		
		echo '<table>';
	}
	
	function data_Admin_StatisticsByEventsViewGuests() {
		$_entity = $this->getAppData()->get_QR_manager_events_statistics();
		
		$this->html_elements->Draw_H1('Statistics by guests. Event "'.$_entity->events->name[0].'" [id '.$_entity->events->id[0].']');
	
		$this->html_elements->Draw_DivStart('block_area_2', '');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin'), '&larr; Administration', 'list_link_back');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'statistics'), '&larr; Statistics', 'list_link_back');
			$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'statistics_by_events'), '&larr; Statistics by event', 'list_link_back');
		$this->html_elements->Draw_DivEnd();
	
		echo '<table class="list_table">
				<tr><td>#</td><td>Date</td><td>Guest</td></tr>';
		$j = 0;
		for ($i = 0; $i < $_entity->len; $i++) {
			if(isset( $_entity->guests[$i]->id[0]) ){
				echo '<tr>';
				echo '<td>'.($j+1).'</td>';
				echo '<td>'.date("d.m.Y H:i", strtotime( $_entity->dates_full[$i] ) ).'</td>';
					
				echo '<td>';
					$_name = 'id '.$_entity->guests[$i]->id[0].'. '.$_entity->guests[$i]->name[0];
					$this->html_elements->Draw_HyperLink($this->setUrl('admin', 'guests_view', $_entity->guests[$i]->id[0]), $_name, 'list_link');
				echo '</td>';
		
				echo '</tr>';
				$j++;
			}
		}
	
		echo '<table>';
	}
	
	
	// END Statistics
	// ______________________________________________________________________________________________________________________
	
	
}
?>