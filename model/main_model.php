<?php 
class MainModel {
	private $db_connect;
	private $app_state;
	private $app_data;
	
	private $db_main_model;
	
	private $if_qr_manager_guests_init = false;
	private $if_qr_manager_guests_reg_types_init = false;
	private $if_qr_manager_events_statistics_init = false;

	function MainModel($_db_connect) {
		$this->setDBConnect($_db_connect);
		
		require_once 'db_main_model.php';
		$this->db_main_model = new DbMainModel();
	}
	
	public function process($_func, $_parram=null) {
		call_user_func( array($this, $_func), $_parram );
	}
	
	
	// ______________________________________________________________________________________________________________________
	// qr_manager_guests
	private function init_QR_manager_guests() {
		if(!$this->if_qr_manager_guests_init){
			include_once 'controller/bd_tables/qr_manager_guests.php';
			$this->app_data->qr_manager_guests = new QR_manager_guests();
			$this->if_qr_manager_guests_init = true;
		}
	}
	
	private function data_Admin_GuestsList() {
		$this->init_QR_manager_guests();
		$this->app_data->qr_manager_guests->getMainList();
		
		$this->init_QR_manager_guests_groups();
		$this->app_data->qr_manager_guests_groups->getFullList();
	}
	
	private function data_Admin_GuestsCreate() {
		$this->init_QR_manager_guests();
		$_id = $this->app_data->qr_manager_guests->createNew();
		$this->app_data->qr_manager_guests->getOneItem($_id);
	}
	
	private function data_Admin_GuestsView($_id) {
		$this->init_QR_manager_guests();
		$this->app_data->qr_manager_guests->getOneItem($_id);
		//$this->app_data->setDataById( 0, $this->db_main_model->guests_GET_List_byId($_id) );
		//$row = $this->db_main_model->guests_GET_EventsActivity_byId($_id);
		//$this->app_data->addDataById( 0, 'events_activity', $row['events_activity'] );	// GET events_activity value and add to $this->app_data->data array
	}
	
	private function data_Admin_GuestsSave($_id) {
		$this->init_QR_manager_guests();
		$this->app_data->qr_manager_guests->updateItem($_id);
		$this->app_data->qr_manager_guests->getOneItem($_id);
		//$this->db_main_model->guests_UPDATE_List_byId($_id);	//!!!!!!!!!!!!!!!!!!
		//$row = $this->db_main_model->guests_GET_EventsActivity_byId($_id);
		//$this->app_data->addDataById( 0, 'events_activity', $row['events_activity'] );	// GET events_activity value and add to $this->app_data->data array
	}
	
	private function data_Admin_GuestsDrop($_id) {
		$this->init_QR_manager_guests();
		$this->app_data->qr_manager_guests->drop($_id);
		$this->data_Admin_GuestsList();
	}
	
	private function data_Admin_GuestsRegisterEvent($_ids) {
		$this->init_QR_manager_guests();
		$this->app_data->qr_manager_guests->setRegistrationByid($_ids[0], $_ids[1]);
		$this->app_data->qr_manager_guests->getOneItem($_ids[0]);
	}
	/**
	 * Package processing.
	 * Adding function
	 */
	private function data_Admin_GuestPackAdd() {
		$this->init_QR_manager_guests();
		$this->app_data->qr_manager_guests->GuestPackAdd();
		$this->data_Admin_GuestsList();
	}
	
	private function data_Admin_GuestPackDrop() {
		$this->init_QR_manager_guests();
		$this->app_data->qr_manager_guests->GuestPackDrop();
		$this->data_Admin_GuestsList();
	}
	
	private function data_Admin_GuestPackMoveToGrope() {
		$this->init_QR_manager_guests();
		$this->app_data->qr_manager_guests->GuestPackMoveToGroup();
		$this->data_Admin_GuestsList();
	}
	
	private function data_Admin_GuestPackNormalizeQr() {
		$this->init_QR_manager_guests();
		$this->app_data->qr_manager_guests->GuestPackNormalizeQr();
		$this->data_Admin_GuestsList();
	}
	
	
	
	// END qr_manager_guests
	// ______________________________________________________________________________________________________________________
	
	
	
	
	// ______________________________________________________________________________________________________________________
	// qr_manager_groups
	
	private function init_QR_manager_guests_groups() {
		include_once 'controller/bd_tables/qr_manager_guests_groups.php';
		$this->app_data->qr_manager_guests_groups = new QR_manager_guests_groups();
	}
	
	function data_Admin_GroupsList() {
		$this->init_QR_manager_guests_groups();
		$this->app_data->qr_manager_guests_groups->getFullList();
	}
	
	function data_Admin_GroupsView($_id) {
		$this->init_QR_manager_guests_groups();
		$this->app_data->qr_manager_guests_groups->getOneItem($_id);
	}
	
	function data_Admin_GroupsSave($_id) {
		$this->init_QR_manager_guests_groups();
		$this->app_data->qr_manager_guests_groups->updateItem($_id);
		$this->app_data->qr_manager_guests_groups->getOneItem($_id);
	}
	
	function data_Admin_GroupsCreate() {
		$this->init_QR_manager_guests_groups();
		$_id = $this->app_data->qr_manager_guests_groups->createNew();
		$this->app_data->qr_manager_guests_groups->getOneItem($_id);
	}
	
	function data_Admin_GroupsCreateMany() {
		$this->init_QR_manager_guests_groups();
		$this->app_data->qr_manager_guests_groups->createMany();
		$this->app_data->qr_manager_guests_groups->getFullList();
	}
	
	function data_Admin_GroupsDrop($_id) {
		$this->init_QR_manager_guests_groups();
		$this->app_data->qr_manager_guests_groups->drop($_id);
		$this->app_data->qr_manager_guests_groups->getFullList();
	}
	
	function data_Admin_DropImgArr($_ids_arr) {
		$this->init_QR_manager_guests_groups();
		$this->app_data->qr_manager_guests_groups->dropImgArr($_ids_arr[0], $_ids_arr[1]);
		$this->app_data->qr_manager_guests_groups->getOneItem($_ids_arr[0]);
	}
	
	// END qr_manager_groups
	// ______________________________________________________________________________________________________________________
	
	
	
	// ______________________________________________________________________________________________________________________
	// qr_manager_guests_reg_types
	private function init_QR_manager_guests_reg_types() {
		if(!$this->if_qr_manager_guests_reg_types_init){
			include_once 'controller/bd_tables/qr_manager_guests_reg_types.php';
			$this->app_data->qr_manager_guests_reg_types = new QR_manager_guests_reg_types();
			$this->if_qr_manager_guests_reg_types_init = true;
		}
	}
	
	function data_Admin_EventsList() {
		$this->init_QR_manager_guests_reg_types();
		$this->app_data->qr_manager_guests_reg_types->getFullList();
	}
	
	function data_Admin_EventsView($_id) {
		$this->app_data->setDataById( 0, $this->db_main_model->regType_GET_typeList_byId($_id) );
	}
	
	function data_Admin_EventsSave($_id) {
		$this->db_main_model->regType_UPDATE_typeList_byId($_id);
		$this->app_data->setDataById( 0, $this->db_main_model->regType_GET_typeList_byId($_id) );
	}
	
	function data_Admin_EventsCreate() {
		$_id = $this->db_main_model->regType_NEW_typeList();
		$this->app_data->setDataById( 0, $this->db_main_model->regType_GET_typeList_byId($_id) );
	}
	
	function data_Admin_EventsDrop($_id) {
		$this->init_QR_manager_guests_reg_types();
		$this->app_data->qr_manager_guests_reg_types->dropOne($_id);
		$this->app_data->qr_manager_guests_reg_types->getFullList();
	}
	
	
	// END qr_manager_guests_reg_types
	// ______________________________________________________________________________________________________________________
	
	
	
	
	// ______________________________________________________________________________________________________________________
	// Statistics
	
	private function init_QR_manager_events_statistics() {
		if(!$this->if_qr_manager_events_statistics_init){
			include_once 'controller/entity_tables/qr_manager_events_statistics.php';
			$this->app_data->qr_manager_events_statistics = new QR_manager_events_statistics();
			$this->if_qr_manager_events_statistics_init = true;
		}
	}
	
	function data_Admin_StatisticsByEvents() {
		$this->init_QR_manager_guests_reg_types();
		$this->app_data->qr_manager_guests_reg_types->getFullList();
	}
	
	function data_Admin_StatisticsByEventsView($_id) {
		$this->init_QR_manager_events_statistics();
		$this->app_data->qr_manager_events_statistics->getDateList($_id);
	}
	
	function data_Admin_StatisticsByEventsViewGuests($_id) {
		$this->init_QR_manager_events_statistics();
		$this->app_data->qr_manager_events_statistics->getGueststListByDate($_id);
	}
	
	// END Statistics
	// ______________________________________________________________________________________________________________________
	
	
	
	// Setters
	function setDBConnect($_db_connect) {
		$this->db_connect = $_db_connect;
	}
	
	function setAppState($_app_state) {
		$this->app_state = $_app_state;
	}
	
	function setAppData($_app_data) {
		$this->app_data = $_app_data;
	}
	
	
	// Getters
	function getDBConnect() {
		return $this->db_connect;
	}
	
	function getData() {
		return $this->data;
	}
}
?>