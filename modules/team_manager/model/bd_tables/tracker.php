<?php
include_once 'model/bd_tables/table_entity.php';
class Tracker extends Table_Entity{
	public $table_name = 'mono';
	
	private	$issue;
	public	$issue_history;
	public	$issue_comments;
	
	public	$filter = Array();
	
	function Tracker() {
	}
	
	protected function setValuesByData($_data) {
	}
	
	public function getMainList() {
	}
	
	public function getOneItem($_id) {
	}
	
	public function createNew() {
	}
	
	public function getTrackerPage() {
		include_once 'model/task_traker/users_issue_filters.php';
		$this->filter[0] = new UsersIssueFilter();
		$this->filter[0]->getOneItemByIserIdAndNumber(Settings::getCurrentUser()->id[0], 1);
		
		$this->filter[1] = new UsersIssueFilter();
		$this->filter[1]->getOneItemByIserIdAndNumber(Settings::getCurrentUser()->id[0], 2);
		
		$this->filter[2] = new UsersIssueFilter();
		$this->filter[2]->getOneItemByIserIdAndNumber(Settings::getCurrentUser()->id[0], 3);
		
		include_once 'model/task_traker/issue.php';
		$this->issue = new Issue();
		$this->issue->getTrackerPage();
	}
	
	public function getEditTaskPage($_id) {
		include_once 'model/task_traker/issue.php';
		$this->issue = new Issue();
		$this->issue->getOneItem($_id);
		
		include_once 'model/task_traker/issue_history.php';
		$this->issue_history = new IssueHistory();
		$this->issue_history->getMainListByIssueId($_id);
		
		include_once 'model/task_traker/issue_comments.php';
		$this->issue_comments = new IssueComments();
		$this->issue_comments->getMainListByIssueId($_id);
	}
	
	public function saveTask($_id) {
		include_once 'model/task_traker/issue.php';
		$this->issue = new Issue();
		$this->issue->updateItem($_id);
	}
	
	public function updateFilter($_id) {
		include_once 'model/task_traker/users_issue_filters.php';
		$this->filter[0] = new UsersIssueFilter();
		$this->filter[0]->updateByIserIdAndNumber(Settings::getCurrentUser()->id[0], $_id);
	}
	
	public function deleteAttach($_attach_id, $_issue_id) {
		include_once 'model/task_traker/issue_attach.php';
		$this->issue_attach = new Attach();
		$this->issue_attach->drop($_attach_id);
	}
	
	public function addTask() {
		include_once 'model/task_traker/issue.php';
		$this->issue = new Issue();
		return $this->issue->createNew();
	}
	
	public function addComment($_issue_id) {
		include_once 'model/task_traker/issue_comments.php';
		$this->issue_comments = new IssueComments();
		$this->issue_comments->createByIssueId($_issue_id);
	}
	
	// GETTERS
	public function getIssue() {
		return $this->issue;
	}
}