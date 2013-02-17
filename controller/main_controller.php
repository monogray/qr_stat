<?php
include_once 'app_state.php';
include_once 'app_data.php';
include_once 'model/main_model.php';
include_once 'view/main_view.php';

class MainController {
	private $db_connect;
	private $app_state;
	private $app_data;
	
	private $model;
	private $view;

	function MainController($_db_connect) {
		$this->setDBConnect($_db_connect);
		
		$this->app_state = new AppState();
		$this->app_data = new AppData();
		
		$this->model = new MainModel( $this->getDBConnect() );
		$this->view = new MainView( $this->getDBConnect() );
	}

	public function process() {
		$this->app_state->getData();
		
		$this->model->setAppState($this->app_state);
		$this->model->setAppData($this->app_data);
		
		$this->view->setAppData($this->app_data);
		
		//$this->controllerProcess();
		
		$this->controllerProcess_siteCore();
	}
	
	private function controllerProcess() {
		include_once 'modules/team_manager/controller/main_controller.php';
		$_controller = new TeamManagerController();
		$_controller->init($this->app_state);
		$_controller->process();
	}
	
	private function controllerProcess_siteCore() {
		// Back-end
		if($this->app_state->getChapter() == 'admin'){
			// Site Framework
			if($this->app_state->getAction() == ''){
				$this->view->draw('draw_Admin_IndexPage');
			}
			else if( substr($this->app_state->getAction(), 0, 9) == 'main_menu' ){
				$this->app_state->setActionInnerByPattern('main_menu');
			
				include_once 'controller/entities/main_menu_controller.php';
				$controller = new MainMenuController($this->app_state);
				$controller->process();
			
				include_once 'view/entities/main_menu_view.php';
				$mainMenuView = new MainMenuView($this->app_state);
				$mainMenuView->setAppData( $controller->getEntity() );
				$mainMenuView->draw();
			}
			else if( substr($this->app_state->getAction(), 0, 5) == 'issue' ){
				$this->app_state->setActionInnerByPattern('issue');
					
				include_once 'controller/entities/issue_controller.php';
				$controller = new IssueController($this->app_state);
				$controller->process();
					
				include_once 'view/entities/issue_view.php';
				$issueView = new IssueView($this->app_state);
				$issueView->setAppData( $controller->getEntity() );
				$issueView->draw();
			}
			else if( substr($this->app_state->getAction(), 0, 16) == 'issue_properties' ){
				$this->app_state->setActionInnerByPattern('issue_properties');
					
				include_once 'controller/entities/issue_properties_controller.php';
				$controller = new IssuePropertiesController($this->app_state);
				$controller->process();
					
				include_once 'view/entities/issue_properties_view.php';
				$issueView = new IssuePropertiesView($this->app_state);
				$issueView->setAppData( $controller->getEntity() );
				$issueView->draw();
			}
		}else {
			include_once 'modules/web_studio/controller/main_controller.php';
			$_controller = new WebStudioController();
			$_controller->init($this->app_state);
			$_controller->process();
		}
	}
	
	private function controllerProcess_qr_stat() {
		// Это для Сайта по играм
		if($this->app_state->getChapter() == 'index'){
			$this->app_state->setActionInnerByPattern('index');		// sub_action actualy
			
			include_once 'modules/web_studio/controller/entities/index_controller.php';
			$controller = new IndexController($this->app_state);
			$controller->process();
				
			include_once 'modules/web_studio/view/main_view.php';
			$issueView = new WebStudioMainView($this->app_state);
			$issueView->setAppData( $controller->getEntity() );
			$issueView->draw();
		}else if($this->app_state->getChapter() == 'index'){
			
		}
		
		// Это для QR
		// ADMIN
		else if($this->app_state->getChapter() == 'admin'){
			if($this->app_state->getAction() == ''){
				$this->view->draw('draw_Admin_IndexPage');
			}
			
			// Guests
			else if($this->app_state->getAction() == 'guests_list'){
				$this->model->process('data_Admin_GuestsList');
				$this->view->draw('draw_Admin_GuestsList');
			}
			else if($this->app_state->getAction() == 'guests_view'){
				$this->model->process('data_Admin_GuestsView', $this->app_state->getId());
				//$this->getDataFor_guests_view();
				$this->view->draw('draw_Admin_GuestsView');
			}
			else if($this->app_state->getAction() == 'guests_save'){
				$this->model->process('data_Admin_GuestsSave', $this->app_state->getId() );
				//$this->getDataFor_guests_view();
				$this->view->draw('draw_Admin_GuestsView');
			}
			else if($this->app_state->getAction() == 'guests_drop'){
				$this->model->process('data_Admin_GuestsDrop', $this->app_state->getId());
				$this->view->draw('draw_Admin_GuestsList');
			}
			else if($this->app_state->getAction() == 'guests_create'){
				$this->model->process('data_Admin_GuestsCreate');
				//$this->getDataFor_guests_view();
				$this->view->draw('draw_Admin_GuestsView');
			}
			// Pack Funcs
			else if($this->app_state->getAction() == 'guests_pack_add'){
				$this->model->process('data_Admin_GuestPackAdd');
				$this->view->draw('draw_Admin_GuestsList');
			}
			else if($this->app_state->getAction() == 'guests_pack_drop'){
				$this->model->process('data_Admin_GuestPackDrop');
				$this->view->draw('draw_Admin_GuestsList');
			}
			else if($this->app_state->getAction() == 'guests_pack_move_to_grop'){
				$this->model->process('data_Admin_GuestPackMoveToGrope');
				$this->view->draw('draw_Admin_GuestsList');
			}
			else if($this->app_state->getAction() == 'guests_pack_normalize_qr'){
				$this->model->process('data_Admin_GuestPackNormalizeQr');
				$this->view->draw('draw_Admin_GuestsList');
			}
			// App Funcs
			else if($this->app_state->getAction() == 'guests_info_preview'){	// Просмотр страницы превью
				$this->model->process('data_Admin_GuestsView', $this->app_state->getId());
				$this->view->draw('draw_Admin_GuestsInfoPreview');
			}
			else if($this->app_state->getAction() == 'guests_register_event'){	// Регистрация на событии
				$this->model->process( 'data_Admin_GuestsRegisterEvent', Array($this->app_state->getId(), $this->app_state->getSubId()) );
				$this->view->draw('draw_Admin_GuestsRegisterEvent');
			}
			
			// Events
			else if($this->app_state->getAction() == 'events_list'){
				$this->model->process('data_Admin_EventsList');
				$this->view->draw('draw_Admin_EventsList');
			}
			else if($this->app_state->getAction() == 'events_view'){
				$this->model->process('data_Admin_EventsView', $this->app_state->getId() );
				$this->view->draw('draw_Admin_EventsView');
			}
			else if($this->app_state->getAction() == 'events_save'){
				$this->model->process('data_Admin_EventsSave', $this->app_state->getId() );
				$this->view->draw('draw_Admin_EventsView');
			}
			else if($this->app_state->getAction() == 'events_create'){
				$this->model->process('data_Admin_EventsCreate', $this->app_state->getId() );
				$this->view->draw('draw_Admin_EventsView');
			}
			else if($this->app_state->getAction() == 'events_drop'){
				$this->model->process('data_Admin_EventsDrop', $this->app_state->getId() );
				$this->view->draw('draw_Admin_EventsList');
			}
			
			// Groups
			else if($this->app_state->getAction() == 'groups_list'){
				$this->model->process('data_Admin_GroupsList');
				$this->view->draw('draw_Admin_GroupsList');
			}
			else if($this->app_state->getAction() == 'groups_view'){
				$this->model->process('data_Admin_GroupsView', $this->app_state->getId());
				$this->view->draw('draw_Admin_GroupsView');
			}
			else if($this->app_state->getAction() == 'groups_save'){
				$this->model->process('data_Admin_GroupsSave', $this->app_state->getId());
				$this->view->draw('draw_Admin_GroupsView');
			}
			else if($this->app_state->getAction() == 'groups_create'){
				$this->model->process('data_Admin_GroupsCreate');
				$this->view->draw('draw_Admin_GroupsView');
			}
			else if($this->app_state->getAction() == 'groups_create_many'){
				$this->model->process('data_Admin_GroupsCreateMany');
				$this->view->draw('draw_Admin_GroupsList');
			}
			else if($this->app_state->getAction() == 'groups_drop_confirm'){
				$this->model->process('data_Admin_GroupsView', $this->app_state->getId());
				$this->view->draw('draw_Admin_GroupsDropConfirm');
			}
			else if($this->app_state->getAction() == 'groups_drop'){
				$this->model->process('data_Admin_GroupsDrop', $this->app_state->getId());
				$this->view->draw('draw_Admin_GroupsList');
			}
			else if($this->app_state->getAction() == 'groups_drop_img_arr'){
				$this->model->process( 'data_Admin_DropImgArr', Array($this->app_state->getId(), $this->app_state->getSubId()) );
				$this->view->draw('draw_Admin_GroupsView');
			}
			
			// Statistics
			else if($this->app_state->getAction() == 'statistics'){
				$this->view->draw('data_Admin_Statistics');
			}
			else if($this->app_state->getAction() == 'statistics_by_events'){
				$this->model->process('data_Admin_StatisticsByEvents');
				$this->view->draw('data_Admin_StatisticsByEvents');
			}
			else if($this->app_state->getAction() == 'statistics_by_events_view'){
				$this->model->process('data_Admin_StatisticsByEventsView', $this->app_state->getId());
				$this->view->draw('data_Admin_StatisticsByEventsView');
			}
			else if($this->app_state->getAction() == 'statistics_by_events_view_guests'){
				$this->model->process('data_Admin_StatisticsByEventsViewGuests', $this->app_state->getId());
				$this->view->draw('data_Admin_StatisticsByEventsViewGuests');
			}
			
			
			// SITE
			// Main Menu
			else if( substr($this->app_state->getAction(), 0, 9) == 'main_menu' ){
				$this->app_state->setActionInnerByPattern('main_menu');
				
				include_once 'controller/entities/main_menu_controller.php';
				$controller = new MainMenuController($this->app_state);
				$controller->process();
				
				include_once 'view/entities/main_menu_view.php';
				$mainMenuView = new MainMenuView($this->app_state);
				$mainMenuView->setAppData( $controller->getEntity() );
				$mainMenuView->draw();
			}
			else if( substr($this->app_state->getAction(), 0, 16) == 'issue_properties' ){
				$this->app_state->setActionInnerByPattern('issue_properties');
					
				include_once 'controller/entities/issue_properties_controller.php';
				$controller = new IssuePropertiesController($this->app_state);
				$controller->process();
					
				include_once 'view/entities/issue_properties_view.php';
				$issueView = new IssuePropertiesView($this->app_state);
				$issueView->setAppData( $controller->getEntity() );
				$issueView->draw();
			}
			else if( substr($this->app_state->getAction(), 0, 5) == 'issue' ){
				$this->app_state->setActionInnerByPattern('issue');
			
				include_once 'controller/entities/issue_controller.php';
				$controller = new IssueController($this->app_state);
				$controller->process();
			
				include_once 'view/entities/issue_view.php';
				$issueView = new IssueView($this->app_state);
				$issueView->setAppData( $controller->getEntity() );
				$issueView->draw();
			}
		}
		/*else if($this->app_state->getChapter() == 'web'){
		}*/
		// END ADMIN
	}
	
	/*private function getDataFor_guests_view() {
		// Get Guests data
		$this->model->process( 'data_Admin_GuestsView', $this->app_state->getId() );
		
		include_once 'controller/bd_tables/qr_manager_guests.php';
		$qr_manager_guests = new QR_manager_guests();
		$qr_manager_guests->setAllValuesByData( $this->app_data->getData() );
		$this->app_data->set_QR_manager_guest($qr_manager_guests);
		
		// Get Events data
		$this->app_data->killData();
		$this->model->process( 'data_Admin_EventsList', $this->app_state->getId() );
		
		include_once 'controller/bd_tables/qr_manager_guests_reg_types.php';
		$qr_manager_guests_reg_types = new QR_manager_guests_reg_types();
		$qr_manager_guests_reg_types->setAllValuesByData( $this->app_data->getData() );
		$this->app_data->set_QR_manager_guest_reg_types($qr_manager_guests_reg_types);
	}
	*/

	
	// Setters
	function setDBConnect($_db_connect) {
		$this->db_connect = $_db_connect;
	}
	
	
	// Getters
	function getDBConnect() {
		return $this->db_connect;
	}
}
?>