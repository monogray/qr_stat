<?php
include_once 'view/view_core.php';
class WebStudioMainView extends View_Core{
	
	function WebStudioMainView($_app_state) {
		$this->setAppState($_app_state);
		
		include_once 'modules/team_manager/view/templates/view_template_default.php';
		$template = new WebStudioDefaultTemplate();
		$this->setTemplate($template);
		
		$this->initLayout();
	}
	
	// Owerride
	public function drawInHeader() {
		echo'<!-- TinyMCE -->
			<script type="text/javascript" src="public/js/tiny_mce/tiny_mce.js"></script>
			<script type="text/javascript" src="modules/team_manager/public/js/draw_main.js"></script>
			<script type="text/javascript">
				initTinyMCE();
				setCurrentUserId('.Settings::getCurrentUserId().');
			</script>
				
			<!-- jQuery UI -->
			<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js" type="text/javascript" charset="utf-8"></script>
			<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" type="text/css" media="screen" charset="utf-8">';
	}
	
	public function draw_template($_process) {
		$this->drawHeader();
			$this->drawTop();
			$this->drawLogo();
			$this->drawMainMenu();
				
			$this->$_process();
				
			$this->drawFooter();
		$this->drawEnd();
	}
	
	public function draw() {
		$this->draw_template( 'process' );
	}
	
	public function drawChat() {
		$this->draw_template( 'processChat' );
	}
	
	public function drawProjects() {
		$this->draw_template( 'processProjects' );
	}
	
	public function drawUsers() {
		$this->draw_template( 'processUsers' );
	}

	/**
	 * Controller for drawing Tracker page
	 */
	public function process(){
		$_controller = Array(	''				=> 'drawMain',
								'edit_task'		=> 'drawEditTask',
								'save_task'		=> 'drawEditTask',
								'update_filter'	=> 'drawMain',
								'delete_attach'	=> 'drawEditTask',
								'edit_task'		=> 'drawEditTask',
								'add_task'		=> 'drawEditTask',
								'add_comment'	=> 'drawEditTask');
		$this->controllerArrayProcess($_controller);
	}
	
	/**
	 * Controller for drawing Chat page
	 */
	public function processChat(){
		$_controller = Array(	''				=> 'drawChatMain',
								'edit'			=> 'drawChatMassages',
								'send'			=> 'drawChatMassages',
								'history'		=> 'drawChatHistory');
		$this->controllerArrayProcess($_controller);
	}
	
	/**
	 * Controller for drawing Project page
	 */
	public function processProjects(){
		$_controller = Array(	''				=> 'drawProjectsMain',
								'edit'			=> 'drawProjectsEdit',
								'save'			=> 'drawProjectsEdit',
								'add_project'	=> 'drawProjectsEdit');
		$this->controllerArrayProcess($_controller);
	}
	
	public function processUsers(){
		$_controller = Array(	''				=> 'drawUsersMain',
								'edit'			=> 'drawUsersEdit');
		$this->controllerArrayProcess($_controller);
	}
	
	public function controllerArrayProcess($_controller_array){
		foreach ($_controller_array as $key => $value)
			if($this->getAppState()->getAction() == $key)
			$this->$value();
	}
	
	public function drawLogin() {
		echo '<style>
			#login_container{
				position:fixed;
				top:8%;
				left:50%;
				width:300px;
				padding:0.5%;
				margin-left:-150px;
				background-color:rgba(0, 0, 0, 0.05);
				border:1px solid rgba(0, 0, 0, 0.1);
				border-radius:5px;
				text-align:center;
			}
			#login_container input{
				text-align:center;
			}
		</style>';
		$this->initFormsLayouts();
		$this->layout->Draw_DivStart('', 'login_container');
			$this->form->formHeader( '' );
				echo '<div>Login</div>';
				$this->form->inputText('', 30, '', 'login');
				echo '<div>Pass</div>';
				echo '<input type="password" name="pass" size="30" />';
				echo '<div>&nbsp;</div>';
				$this->form->formSubmit('Login');
			$this->form->formFooter();
		$this->layout->Draw_DivEnd('login_container');
	}
	
	public function drawLogo() {
		$this->layout->Draw_DivStart('', 'logo');
			echo '<img src="modules/team_manager/public/img/tm_logo.png" />';
		$this->layout->Draw_DivEnd('logo');
	}
	
	public function drawMainMenu() {
		$this->layout->Draw_DivStart('', 'logout');
			$this->layout->Draw_HyperLink('?logout=true',  'Logout', 'main_menu_link');
		$this->layout->Draw_DivEnd('logout');
		
		$this->layout->Draw_DivStart('', 'online_users');
			echo '<b>Online</b>';
			$this->layout->Draw_DivStart('', 'online_users_content');
			$this->layout->Draw_DivEnd('online_users_content');
		$this->layout->Draw_DivEnd('online_users');
		
		$this->layout->Draw_DivStart('', 'main_menu_container');
			$this->layout->Draw_HyperLink( $this->setUrl('index'),		'Tracker', 'main_menu_link' );
			$this->layout->Draw_HyperLink( $this->setUrl('project'),	'Projects', 'main_menu_link' );
			$this->layout->Draw_HyperLink( $this->setUrl('users'),		'Users', 'main_menu_link' );
			$this->layout->Draw_HyperLink( $this->setUrl('chat'),		'Chat', 'main_menu_link' );
			//$this->layout->Draw_HyperLink( $this->setUrl('mail'),		'Mail', 'main_menu_link' );
			//$this->layout->Draw_HyperLink( $this->setUrl('ad'),			'Ad', 'main_menu_link' );
		$this->layout->Draw_DivEnd('main_menu_container');
	}
	
	// ________________________________________________________________________________________
	// TRACKER
	public function drawMain() {
		$_entity = $this->getAppData();
		$_issue = $_entity->getIssue();
		$_filter[0] = $_entity->filter[0];
		$_filter[1] = $_entity->filter[1];
		$_filter[2] = $_entity->filter[2];
		
		$this->initFormsLayouts();
		
		$this->layout->Draw_DivStart('tracker_container', '');
			$this->layout->Draw_H1('Tracker');
			$this->layout->Draw_HyperLink( $this->setUrl('index', 'add_task'), 'Add task <img src="modules/team_manager/public/img/add_ico.png" width="20px"/>', 'add_task_link' );
			
			// Draw Tracker areas
			$this->drawTrakerArea($_issue, $_filter[0], 1);
			$this->drawTrakerArea($_issue, $_filter[1], 2);
			$this->drawTrakerArea($_issue, $_filter[2], 3);
		$this->layout->Draw_DivEnd('tracker_container');
	}
	
	public function drawTrakerArea($_issue, $_filter, $_filter_number) {
		if($_filter_number == 1){
			$_class = 'tracker_inner_container_100';
		}else{
			$_class = 'tracker_inner_container_50';
		}
		
		Entity::inst()->init('div', 'class', $_class)
			->addAttr('name', $_filter_number)->drawHeader();
		
		$this->layout->Draw_DivStart('tracker_caption', '');
			echo 'Trackers area '.$_filter_number;
		$this->layout->Draw_DivEnd('tracker_caption');
		
		// Filter
		$this->layout->Draw_DivStart('tracker_filter', '');
			echo '<div class="tracker_filter_caption">Filter</div>';
			$this->form->formHeader( '?chapter=index&action=update_filter&id='.$_filter_number );
				
			$_proj_ids =  $_issue->project_one_entity->id;
			array_unshift($_proj_ids, "-1");
				
			$_proj_names =  $_issue->project_one_entity->name;
			array_unshift($_proj_names, "All");
				
			$_stat_ids =  $_issue->status_one_entity->id;
			array_unshift($_stat_ids, '-1', '-2');
			
			$_stat_names =  $_issue->status_one_entity->name_eng;
			array_unshift($_stat_names, 'All', 'Not close');
			
			$this->layout->Draw_DivStart('div_left_30', '');
				$this->form->inputSelect('Project ', 'project', $_proj_ids, $_filter->project_id[0], $_proj_names);
			$this->layout->Draw_DivEnd('div_left_30');
			$this->layout->Draw_DivStart('div_left_30', '');
				$this->form->inputSelect('Status ', 'status', $_stat_ids, $_filter->status[0], $_stat_names);
			$this->layout->Draw_DivEnd('div_left_30');
			
			$assigned_to_ids = $_issue->users_one_entity->id;
			$assigned_to_names = $_issue->users_one_entity->name;
			array_unshift($assigned_to_names, 'All');
			array_unshift($assigned_to_ids, "-1");
			$this->layout->Draw_DivStart('div_left_30', '');
				$this->form->inputSelect('Assigned to ', 'assigned_to', $assigned_to_ids, $_filter->assigned_to[0], $assigned_to_names);
			$this->layout->Draw_DivEnd('div_left_30');
			
			$author_ids = $_issue->users_one_entity->id;
			$author_names = $_issue->users_one_entity->name;
			array_unshift($author_names, 'All');
			array_unshift($author_ids, "-1");
			$this->layout->Draw_DivStart('div_left_30', '');
				$this->form->inputSelect('Author ', 'author', $author_ids, $_filter->author[0], $author_names);
			$this->layout->Draw_DivEnd('div_left_30');
			
			$this->form->formSubmit('Apply filter');
			$this->form->formFooter();
		$this->layout->Draw_DivEnd('tracker_filter');
		
		// Issues
		echo '<table class="tracker_issue_table"><tr><td>id</td><td><img src="modules/team_manager/public/img/attach_ico.png" height="19px" style="margin-top:4px;"/></td><td>Summary</td><td>Project</td><td>Assigned to</td><td>Priority</td><td>Status</td><td>Type</td><td>Advance</td></tr>';
		for ($i = 0; $i < $_issue->len; $i++) {
			if( ($_filter->project_id[0] == -1 || $_filter->project_id[0] == $_issue->project[$i]) &&
				(($_filter->status[0] == -1 || $_filter->status[0] == $_issue->status[$i]) || ($_filter->status[0] == -2 && $_issue->status[$i] <= 3 )) &&
				($_filter->assigned_to[0] == -1 || $_filter->assigned_to[0] == $_issue->assigned_to[$i]) &&
				($_filter->author[0] == -1 || $_filter->author[0] == $_issue->author[$i])
			){
		
				$_class = '';
				if($_issue->priority_entity[$i]->id[0] == '1'){				// High priority fot tr
					$_class = 'rad_issue';
				}else if($_issue->priority_entity[$i]->id[0] == '2'){		// Medium priority fot tr
					$_class = 'blue_issue';
				}else if($_issue->priority_entity[$i]->id[0] == '3'){		// Low priority	fot tr
					$_class = 'green_issue';
				}
				
				if($_issue->status_entity[$i]->id[0] == '4'){				// Close status fot tr
					$_class = '';
				}
		
				$_class_td = '';
				if($_issue->type_entity[$i]->id[0] == '1'){					// Bug Type	fot td
					$_class_td = 'rad_td';
				}
		
				$_class_status_td = '';
				if($_issue->status_entity[$i]->id[0] == '1'){				// Open status for td
					$_class_status_td = 'green_td';
				}else if($_issue->status_entity[$i]->id[0] == '3'){			// Wait status for td
					$_class_status_td = 'yellow_td';
				}
					
				echo '<tr class="'.$_class.'">';
				echo '<td name="smal_font_td">';
					echo  $_issue->id[$i];
				echo '</td>';
				
				echo '<td name="smal_font_td">';
					if($_issue->attach_entity[$i]->len != 0)
						echo  $_issue->attach_entity[$i]->len;
				echo '</td>';
				
				echo '<td>';
					$this->layout->Draw_HyperLink($this->setUrl('index', 'edit_task', $_issue->id[$i]),  $_issue->summary[$i]);
				echo '</td>';
				echo '<td name="middle_font_td_left">';
					$this->layout->Draw_HyperLink( $this->setUrl('project', 'edit', $_issue->project_entity[$i]->id[0] ),$_issue->project_entity[$i]->name[0], '' );
				echo '</td>';
				echo '<td name="smal_font_td">';
					echo  $_issue->author_entity[$i]->name[0];
					echo '&nbsp;&#9658;<br/>';
					echo  $_issue->assigned_to_entity[$i]->name[0];
				echo '</td>';
				echo '<td name="smal_font_td">';
					echo  $_issue->priority_entity[$i]->name_eng[0];
				echo '</td>';
				echo '<td class="'.$_class_status_td.'" name="smal_font_td">';
					echo  $_issue->status_entity[$i]->name_eng[0];
				echo '</td>';
				echo '<td class="'.$_class_td.'" name="smal_font_td">';
					echo  $_issue->type_entity[$i]->name_eng[0];
				echo '</td>';
				echo '<td>';
					echo '<div style="float:left;height:15px;width:'.$_issue->progress_entity[$i]->percents[0].'%;background-color:rgba(0, 0, 0, 0.5);border-radius:3px;"></div>';
				echo '</td>';
		
				echo '</tr>';
			}
		}
		echo '</table>';
		$this->layout->Draw_DivEnd('tracker_inner_container/$_class');
	}
	
	public function drawEditTask() {
		$_entity	= $this->getAppData();
		$_issue		= $_entity->getIssue();
		$_history	= $_entity->issue_history;
		$_comments	= $_entity->issue_comments;
		$_attach	= $_issue->attach_entity[0];
		
		$this->initFormsLayouts();
		
		$this->layout->Draw_DivStart('tracker_container', '');
		$this->layout->Draw_HyperLink($this->setUrl('index'),  '&larr; back to tracker', 'back_link');
		Div::getNew()->init('class', 'separator_1')->draw();
		$this->layout->Draw_H1($_issue->id[0].'. '.$_issue->summary[0]);
		
		$this->form->formHeader( '?chapter=index&action=save_task&id='.$_issue->id[0] );
		
		$this->form->formSubmit('Save');
		
		Div::getNew()->init('class', 'separator_1')->draw();
		
		Div::getNew()->init('class', 'tracker_inner_area')->drawHeader();
			$this->form->inputText('Summary ', 50, $_issue->summary[0], 'summary');
			$this->form->inputSelect('Project ', 'project', $_issue->project_one_entity->id, $_issue->project[0], $_issue->project_one_entity->name);
		Div::getNew()->drawFooter('tracker_inner_area');
		
		Div::getNew()->init('class', 'separator_1')->draw();
		
		$this->layout->Draw_DivStart('tracker_inner_area', '');
			$this->form->inputTextarea('Description ', 10, 10, $_issue->steps_to_reproduce[0], 'steps_to_reproduce', 'edit_textfield');
		$this->layout->Draw_DivEnd('tracker_inner_area');
		
		// Properties
		$this->layout->Draw_H2('Properties');
		$this->layout->Draw_DivStart('tracker_inner_area', '');
			$this->form->inputSelect('Issue type ', 'type', $_issue->type_one_entity->id, $_issue->type[0], $_issue->type_one_entity->name_eng);
		$this->layout->Draw_DivEnd('tracker_inner_area');
		
		$this->layout->Draw_DivStart('tracker_inner_area', '');
			$this->layout->Draw_DivStart('div_left_30', '');
				echo 'Author: '.$_issue->author_entity[0]->name[0];
			$this->layout->Draw_DivEnd('');
			
			$this->layout->Draw_DivStart('div_left_30', '');
				$this->form->inputSelect('Assigned to ', 'assigned_to', $_issue->users_one_entity->id, $_issue->assigned_to[0], $_issue->users_one_entity->name);
			$this->layout->Draw_DivEnd('div_left_30');
		$this->layout->Draw_DivEnd('tracker_inner_area');
		
		$this->layout->Draw_DivStart('tracker_inner_area', '');
			$this->layout->Draw_DivStart('div_left_30', '');
				$this->form->inputSelect('Priority ', 'priority', $_issue->priority_one_entity->id, $_issue->priority[0], $_issue->priority_one_entity->name_eng);
			$this->layout->Draw_DivEnd('div_left_30');
			
			$this->layout->Draw_DivStart('div_left_30', '');
				$this->form->inputSelect('Status ', 'status', $_issue->status_one_entity->id, $_issue->status[0], $_issue->status_one_entity->name_eng);
			$this->layout->Draw_DivEnd('div_left_30');
			
			$this->layout->Draw_DivStart('div_left_30', '');
				$this->form->inputSelect('Progress ', 'progress', $_issue->progress_one_entity->id, $_issue->progress[0], $_issue->progress_one_entity->percents);
			$this->layout->Draw_DivEnd('div_left_30');
		$this->layout->Draw_DivEnd('tracker_inner_area');
		
		// Dates
		echo'<script>
			$(function() {
				$( "#start_date" ).datepicker({
					showButtonPanel: true
				});
				$( "#execute_to" ).datepicker({
					showButtonPanel: true
				});
				$( "#start_date" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
				$( "#execute_to" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
				
				$( "#start_date" ).datepicker("setDate", "'.substr($_issue->start_date[0], 0, 9).'");
				$( "#execute_to" ).datepicker("setDate", "'.substr($_issue->execute_to[0], 0, 9).'");
			});
		</script>';
		Entity::inst()->init('div', 'class', 'tracker_inner_area')->drawHeader();
			$this->form->inputText('Start Date ', 30, '', 'start_date', 'start_date');
			$this->form->inputText('Execute To ', 30, '', 'execute_to', 'execute_to');
		Entity::inst()->drawFooter_st('div', 'tracker_inner_area');
		
		// Attachments
		$this->layout->Draw_H2('Attachments ('.$_attach->len.')');
		$this->layout->Draw_DivStart('tracker_inner_area', '');
			$this->form->inputFile('', 'attach[]', true);
			echo '<table class="tracker_issue_table" name="attach_table"><tr><td>id</td><td>Preview</td><td>Path</td><td>Author</td><td>Date</td><td>Drop</td></tr>';
			for ($i = 0; $i < $_attach->len; $i++) {
				echo '<tr>';
				echo '<td>';
					echo  $_attach->id[$i];
				echo '</td>';
				echo '<td>';
					$rest = substr($_attach->path[$i], -3);
					if($rest == 'jpg' || $rest == 'bmp' || $rest == 'png')
						$_src = Settings::$path_to_attachments_dir.$_attach->path[$i];
					else 
						$_src = 'modules/team_manager/public/img/file_icon.png';
					echo  '<a href="'.Settings::$path_to_attachments_dir.$_attach->path[$i].'" target="_blank"><img src="'.$_src.'" width="25px"></a>';
				echo '</td>';
				echo '<td>';
					echo  '<a href="'.Settings::$path_to_attachments_dir.$_attach->path[$i].'" target="_blank">'.$_attach->path[$i].'</a>';
				echo '</td>';
				echo '<td>';
					echo  $_attach->author_entity[$i]->name[0];
				echo '</td>';
				echo '<td>';
					echo date("d.m.Y H:i (l)", strtotime( $_attach->date[$i] ) );
				echo '</td>';
				echo '<td>';
					echo  '<div class="attach_drop_bt">Drop</div>';
					echo  '<div class="attach_drop_confirm_bt" value="'.$_attach->id[$i].'">confirm</div>';
				echo '</td>';
				echo '</tr>';
			}
			echo '</table>';
		$this->layout->Draw_DivEnd('tracker_inner_area');
		
		$this->form->formSubmit('Save');
		$this->form->formFooter();
		
		// Attachments deleting processing
		echo'<script>
			$(document).ready(function() {
				$(".attach_drop_confirm_bt").toggle("middle");
			});
		
			$(".attach_drop_bt").click(function() {
				var _value = $(".attach_drop_bt").index(this);
				$(".attach_drop_confirm_bt:eq("+_value+")").toggle("fast");
			});
				
			$(".attach_drop_confirm_bt").click(function() {
				window.location = "?chapter=index&action=delete_attach&id=" + '.$_issue->id[0].' + "&attach_id=" + $(this).attr("value");
			});
		</script>';
		
		$this->layout->Draw_DivEnd('tracker_container');
		
		// History
		$this->layout->Draw_DivStart('tracker_container', '');
			$this->layout->Draw_H2('History');
			$this->layout->Draw_DivStart('tracker_inner_area', '');
			echo '<div id="history_open_bt">Open +</div>';
			$this->layout->Draw_DivStart('', 'history_inner_area');
				echo '<table class="tracker_issue_table"><tr><td>id</td><td>Category</td><td>Old value</td><td>New Value</td><td>Author</td><td>Date</td></tr>';
				for ($i = 0; $i < $_history->len; $i++) {
					echo '<tr class="small_font_tr">';
					echo '<td>';
						echo  $_history->id[$i];
					echo '</td>';
					echo '<td>';
						echo  $_history->category_entity[$i]->name_eng[0];
					echo '</td>';
					echo '<td>';
						echo  '<div class="history_content_areas">'.$_history->old_value[$i].'</div>';
					echo '</td>';
					echo '<td>';
						echo  '<div class="history_content_areas">'.$_history->new_value[$i].'</div>';
					echo '</td>';
					echo '<td>';
						echo  $_history->author_entity[$i]->name[0];
					echo '</td>';
					echo '<td>';
						echo  date("d.m.Y H:i (l)", strtotime( $_history->date[$i] ) );
					echo '</td>';
					echo '</tr>';
				}
				echo '</table>';
			$this->layout->Draw_DivEnd('history_inner_area');
		$this->layout->Draw_DivEnd('tracker_inner_area');
		
		echo'<script>
			$(document).ready(function() {
				$("#history_inner_area").toggle("middle");
			});
			
			$("#history_open_bt").click(function() {
  				$("#history_inner_area").toggle("middle");
				$("#history_inner_area").css("overflow-y", "scroll");
			});
				
		</script>';
		$this->layout->Draw_DivEnd('tracker_container');
		
		// Comments
		$this->layout->Draw_DivStart('tracker_container', '');
		$this->layout->Draw_H1('Comments');
		
		$this->layout->Draw_DivStart('tracker_inner_area', '');
		$this->initFormsLayouts();
			$this->form->formHeader( '?chapter=index&action=add_comment&id='.$_issue->id[0] );
			$this->form->inputTextarea('Message ', 10, 10, '', 'comment', 'chat_messages_textarea');
			$this->form->formSubmit('Add comment');
		$this->form->formFooter();
		$this->layout->Draw_DivEnd('tracker_inner_area');
		
		Div::getNew()->init('class', 'separator_1')->draw();
		
		echo '<table class="tracker_issue_table"><tr><td>id</td><td>Comment</td><td>Author</td><td>Date</td></tr>';
		for ($i = 0; $i < $_comments->len; $i++) {
			echo '<tr>';
			echo '<td name="smal_font_td">';
				echo  $_comments->id[$i];
			echo '</td>';
			echo '<td>';
				echo  $_comments->comment[$i];
			echo '</td>';
			echo '<td name="smal_font_td">';
				echo  $_comments->author_entity[$i]->name[0];
			echo '</td>';
			echo '<td name="smal_font_td">';
				echo  date("d.m.Y H:i (l)", strtotime( $_comments->date[$i] ) );
			echo '</td>';
			echo '</tr>';
		}
		echo '</table>';
		
		$this->layout->Draw_DivEnd('tracker_container');
	}
	
	// _________________________________________________________________________________
	// CHAT
	public function drawChatMain() {
		$_entity = $this->getAppData();
		$_chat = $_entity->getChat();
	
		$this->layout->Draw_DivStart('tracker_container', '');
		$this->layout->Draw_H1('Chat');
		
		// Issues
		echo '<table class="tracker_issue_table"><tr><td>id</td><td>Name</td><td>Author</td><td>Project</td></tr>';
		for ($i = 0; $i < $_chat->len; $i++) {
			echo '<tr>';
			echo '<td name="smal_font_td">';
				echo  $_chat->id[$i];
			echo '</td>';
			echo '<td>';
				$this->layout->Draw_HyperLink($this->setUrl('chat', 'edit', $_chat->id[$i]),  $_chat->name[$i]);
			echo '</td>';
			echo '<td name="smal_font_td">';
				echo  $_chat->author_entity[$i]->name[0];
			echo '</td>';
			echo '<td name="smal_font_td">';
				echo  $_chat->project_entity[$i]->name[0];
			echo '</td>';
			echo '</tr>';
		}
		echo '</table>';
		
		$this->layout->Draw_DivEnd('tracker_container');
	}
	
	public function drawChatMassages() {
		$_entity = $this->getAppData();
		$_chat_messages = $_entity->chat_messages;
		
		echo'<script>
		var prev_height = 0;
		$(document).ready(function() {
			update();
			setInterval(update, 12000);
		});
				
		function update(){
			$.ajax({
				url: "modules/team_manager/controller/ajax/update_chat.php?id='.$_chat_messages->chat_one_entity->id[0].'",
			}).done(function(msg) {
				var _obj = $("<div></div>");
				_obj.html( msg );
				if( _obj.html() != $("#chat_messages_content").html() ){
					$("#chat_messages_content").html( msg );
					scroll();
				}
			});
		}
				
		function scroll(){
			var _h = $("#chat_messages_content table").height();
			if(prev_height != _h){
				$("#chat_messages_content").animate({
					scrollTop: _h
				}, 500);
				prev_height = _h;
			}
		}
		</script>';
	
		$this->layout->Draw_DivStart('tracker_container', '');
		$this->layout->Draw_HyperLink($this->setUrl('chat'),  '&larr; back to chats list', 'back_link');
		$this->layout->Draw_H1($_chat_messages->chat_one_entity->name[0]);
	
		$this->layout->Draw_HyperLink($this->setUrl('chat', 'history', $_chat_messages->chat_one_entity->id[0]),  '<img src="modules/team_manager/public/img/history_ico.png" width="20px"/> History', 'right_link');
		
		// Messages
		$this->layout->Draw_DivStart('', 'chat_messages_content');
		echo '<table class="tracker_issue_table"><tr><td>id</td><td>Message</td><td>Author</td><td>Date</td></tr>';
			echo '<tr><td colspan="4">LOADING...</td></tr>';
		echo '</table>';
		$this->layout->Draw_DivEnd('chat_messages_content');
		
		$this->initFormsLayouts();
			$this->form->formHeader( '?chapter=chat&action=send&id='.$_chat_messages->chat_one_entity->id[0] );
			$this->form->inputTextarea('Message ', 10, 10, '', 'message', 'chat_messages_textarea');
			$this->form->formSubmit('Send');
		$this->form->formFooter();
	
		$this->layout->Draw_DivEnd('tracker_container');
	}
	
	public function drawChatHistory() {
		$_entity = $this->getAppData();
		$_chat_messages = $_entity->chat_messages;
	
		$this->layout->Draw_DivStart('tracker_container', '');
		$this->layout->Draw_HyperLink($this->setUrl('chat', 'edit', $_chat_messages->chat_one_entity->id[0]),  '&larr; back to chat', 'back_link');
		$this->layout->Draw_H1('Chat');
	
		// Messages
		$this->layout->Draw_DivStart('', 'chat_messages_content');
		echo '<table class="tracker_issue_table"><tr><td>id</td><td>Message</td><td>Author</td><td>Date</td></tr>';
		for ($i = 0; $i < $_chat_messages->len; $i++) {
			echo '<tr>';
			echo '<td name="smal_font_td">';
				echo  $_chat_messages->id[$i];
			echo '</td>';
			echo '<td>';
				echo  $_chat_messages->message[$i];
			echo '</td>';
			echo '<td class="chat_small_td">';
				echo $_chat_messages->author_entity[$i]->name[0];
			echo '</td>';
			echo '<td class="chat_small_td">';
				echo  $_chat_messages->date[$i];
			echo '</td>';
			echo '</tr>';
		}
		echo '</table>';
		$this->layout->Draw_DivEnd('chat_messages_content');
		$this->layout->Draw_DivEnd('tracker_container');
	}
	
	// _________________________________________________________________________________
	// PROJECTS
	public function drawProjectsMain() {
		$_entity = $this->getAppData();
		$_proj = $_entity->getProject();
	
		$this->layout->Draw_DivStart('tracker_container', '');
		$this->layout->Draw_H1('Projects');
		
		$this->layout->Draw_HyperLink( $this->setUrl('project', 'add_project'), 'Add project <img src="modules/team_manager/public/img/add_ico.png" width="20px"/>', 'add_task_link' );
	
		// Projects table
		echo '<table class="tracker_issue_table"><tr><td>id</td><td>Name</td><td>Author</td><td>Date</td><td name="smal_font_td">Active issues</td><td name="smal_font_td">Closed issues</td></tr>';
		for ($i = 0; $i < $_proj->len; $i++) {
			echo '<tr>';
			echo '<td name="smal_font_td">';
				echo  $_proj->id[$i];
			echo '</td>';
			echo '<td>';
				$this->layout->Draw_HyperLink($this->setUrl('project', 'edit', $_proj->id[$i]),  $_proj->name[$i]);
			echo '</td>';
			echo '<td class="chat_small_td">';
				echo  $_proj->author_entity[$i]->name[0];
			echo '</td>';
			echo '<td class="chat_small_td">';
				echo  date("d.m.Y H:i (l)", strtotime( $_proj->date[$i] ) );
			echo '</td>';
			echo '<td class="chat_small_td">';
				$_issue_count = $_proj->getIssueCountByProjectId( $_proj->id[$i] );
				echo  $_issue_count[0];
			echo '</td>';
			echo '<td class="chat_small_td">';
				echo  $_issue_count[1];
			echo '</td>';
			echo '</tr>';	
		}
		echo '</table>';
	
		$this->layout->Draw_DivEnd('tracker_container');
	}
	
	public function drawProjectsEdit() {
		$_entity = $this->getAppData();
		$_proj = $_entity->getProject();
	
		$this->layout->Draw_DivStart('tracker_container', '');
		$this->layout->Draw_HyperLink($this->setUrl('project'),  '&larr; back to projects', 'back_link');
		
		$this->layout->Draw_H1( $_proj->name[0]);
	
		$this->initFormsLayouts();
		$this->form->formHeader( '?chapter=project&action=save&id='.$_proj->id[0] );
			$this->layout->Draw_DivStart('tracker_inner_area', '');
				// Description
				$this->form->inputTextarea('Description ', 10, 10, $_proj->description[0], 'description', 'chat_messages_textarea');
			$this->layout->Draw_DivEnd('tracker_inner_area');
			
			$this->layout->Draw_DivStart('tracker_inner_area', '');
				// Author
				$this->layout->Draw_DivStart('div_left_30', '');
					echo 'Author: '.$_proj->author_entity[0]->name[0];
				$this->layout->Draw_DivEnd('');
				// Coordinator
				$this->layout->Draw_DivStart('div_left_30', '');
					$this->form->inputSelect('Coordinator ', 'coordinator', $_proj->users_one_entity->id, $_proj->coordinator_entity[0]->id[0], $_proj->users_one_entity->name);
				$this->layout->Draw_DivEnd('');
			$this->layout->Draw_DivEnd('tracker_inner_area');
			
			$this->layout->Draw_DivStart('tracker_inner_area', '');
				// Status
				$this->layout->Draw_DivStart('div_left_30', '');
				$this->form->inputSelect('Status ', 'status', $_proj->status_one_entity->id, $_proj->status_entity[0]->id[0], $_proj->status_one_entity->name_eng);
				$this->layout->Draw_DivEnd('');
			$this->layout->Draw_DivEnd('tracker_inner_area');
			
		$this->form->formSubmit('Save');
		$this->form->formFooter();
	
		$this->layout->Draw_DivEnd('tracker_container');
	}
	
	
	// _________________________________________________________________________________
	// USERS
	public function drawUsersMain() {
		$_entity = $this->getAppData();
		$_user = $_entity->getUsers();
	
		$this->layout->Draw_DivStart('tracker_container', '');
		$this->layout->Draw_H1('Users');
	
		Entity::inst()->init('a', 'class', 'add_task_link')
			->addAttr('href', $this->setUrl('users', 'add'))
			->setContent('Add user <img src="modules/team_manager/public/img/add_ico.png" width="20px"/>')
			->draw();
		
		// Users table
		echo '<table class="tracker_issue_table"><tr><td>id</td><td>Name</td><td>Mail</td><td>Phone</td></tr>';
		for ($i = 0; $i < $_user->len; $i++) {
			echo '<tr>';
			echo '<td name="smal_font_td">';
				echo  $_user->id[$i];
			echo '</td>';
			echo '<td>';
				$this->layout->Draw_HyperLink($this->setUrl('users', 'edit', $_user->id[$i]),  $_user->name[$i]);
			echo '</td>';
			echo '<td class="chat_small_td">';
				echo  $_user->mail[$i];
			echo '</td>';
			echo '<td class="chat_small_td">';
				echo  $_user->phone[$i];
			echo '</td>';
			echo '</tr>';
		}
		echo '</table>';
	
		$this->layout->Draw_DivEnd('tracker_container');
	}
	
	public function drawUsersEdit() {
		$_entity = $this->getAppData();
		$_user = $_entity->getUsers();
	
		Div::getNew()->init('class', 'tracker_container')->drawHeader();
		
		Entity::inst()->init('a', 'class', 'back_link')
			->addAttr('href', $this->setUrl('users'))
			->setContent('&larr; back to users')
			->draw();
		
		Entity::inst()->init('h1')->setContent($_user->name[0])->draw();

		Entity::inst()->init('div', 'class', 'tracker_inner_area')
			->drawHeader();
		Entity::inst()->init('div', '', '')
			->setContent('Login: '.$_user->login[0])
			->draw()
			->setContent('e-mail: '.$_user->mail[0])
			->draw()
			->setContent('Skype: '.$_user->skype[0])
			->draw()
			->setContent('icq: '.$_user->icq[0])
			->draw()
			->setContent('Phone: '.$_user->phone[0])
			->draw();
		Entity::drawFooter_st('div', 'tracker_inner_area');
		
		Entity::inst()->init('div', 'class', 'tracker_inner_area')
			->setContent(
				Entity::getNew()->init('div', '', '')
				->setContent('Personal Data:')
			)
			->addContent(
				Entity::getNew()->init('div', '', '')
				->setContent($_user->personal_data[0])
			)
			->draw();
			
		Entity::inst()->init('div', 'class', 'tracker_inner_area')
			->setContent(
				Entity::getNew()->init('div', '', '')
				->setContent('Date: '.date("d.m.Y H:i (l)", strtotime( $_user->date[0] ) ))
			)
			->draw();
		
		Div::drawFooter_st('tracker_container');
	}
}
?>