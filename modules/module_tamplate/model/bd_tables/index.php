<?php
include_once 'model/bd_tables/table_entity.php';
class Index extends Table_Entity{
	public $table_name = 'qr_manager';
	
	private $main_menu;
	private $issue;
	
	function Index() {
	}
	
	protected function setValuesByData($_data) {
	}
	
	public function getMainList() {
	}
	
	public function getOneItem($_id) {
	}
	
	public function createNew() {
	}
	
	public function getPage() {
		include_once 'model/bd_tables/main_menu.php';
		$this->main_menu = new MainMenu();
		$this->main_menu->getMainList();
		
		include_once 'model/bd_tables/issue.php';
		$this->issue = new Issue();
		$this->issue->getListByChapterId( $this->main_menu->getIdCurrentChapter() );
	}
	
	// GETTERS
	public function getMainMenu() {
		return $this->main_menu;
	}
	
	public function getIssue() {
		return $this->issue;
	}
}