<?php
//include_once 'model/bd_tables/table_entity.php';
class ContentModel{		// extends Table_Entity{
	public $table_name = 'content_model';
	
	protected $main_menu;
	protected $issue;
	
	function ContentModel() {
		include_once 'model/bd_tables/main_menu.php';
		include_once 'model/bd_tables/issue.php';
	}
	
	public function getIndex() {
		$this->main_menu = MainMenu::getInstance();
	}
	
	// MAIN MENU
	public function getMainMenuView($_main_menu_id) {
		$this->main_menu = MainMenu::getInstance();
		$this->issue = new Issue();
		$this->issue->getListByChapterId($_main_menu_id);
	}
	
	// ISSUE
	public function getMainIssueView($_issue_id) {
		$this->main_menu = MainMenu::getInstance();
		$this->issue = new Issue();
		$this->issue->getOneItem($_issue_id);
	}
	
	public function processIssueUpdate($_issue_id) {
		$this->main_menu = MainMenu::getInstance();
		$this->issue = new Issue();
		
		if($_GET['type'] == 1)
			$this->issue->updateItem_portfolio($_issue_id);
		if($_GET['type'] == 2)
			$this->issue->updateItem_text_block($_issue_id);
		
		$this->issue->getOneItem($_issue_id);
	}
	
	public function processIssueAdd($_main_menu_id, $_type) {
		$this->issue = new Issue();
		$this->issue->createNewByChapterIdAndType($_main_menu_id, $_type);
		
		$this->main_menu = MainMenu::getInstance();
		$this->issue->getListByChapterId($_main_menu_id);
	}
	
	public function processIssueDrop($_issue_id) {
		$this->issue = new Issue();
		// Get parrents id of dropped issue
		$this->issue->getOneItem($_issue_id);
		$_main_menu_id = $this->issue->menu[0];		// !!!!!!!!!! Тут возможно и parent_id
		
		// Drop Issue
		$this->issue->drop($_issue_id);
		
		$this->main_menu = MainMenu::getInstance();
		$this->issue->getListByChapterId($_main_menu_id);
	}
	
	
	// GETTERS
	public function getMainMenu() {
		return $this->main_menu;
	}
	
	public function getIssue() {
		return $this->issue;
	}
}