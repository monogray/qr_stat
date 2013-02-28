<?php 
/**
 * Url parsing and save all url data
 */
class AppState {
	public $chapter;
	public $action;
	public $action_inner;
	public $id;
	public $sub_id;
	// User Data
	//public $user_logined = false;

	function AppState() {
	}
	
	public function getData() {
		$this->getDataFromSESSIONS();
		$this->getDataFromGET();
		$this->getDataFromPOST();
	}
	
	private function getDataFromSESSIONS() {
	}
	
	
	// GET PROCESSING
	private function getDataFromGET() {
		$this->setChapter( $this->getChapterFromGET() );
		$this->setAction( $this->getActionFromGET() );
		$this->setId( $this->getIdFromGET() );
		$this->setSubId( $this->getSubIdFromGET() );
	}
	
	private function getChapterFromGET() {
		if(!isset($_GET['chapter']))
			return 'index';
		else{
			return $this->secureGet($_GET['chapter']);
		}
	}
	
	private function getActionInnerByPattern($_pat='') {
		return str_replace($_pat.'_', "", $this->getAction());
	}
	
	private function getActionFromGET() {
		if(!isset($_GET['action']))
			return '';
		else{
			return $this->secureGet($_GET['action']);
		}
	}
	
	private function getIdFromGET() {
		if(!isset($_GET['id']))
			return 0;
		else{
			return $this->secureGet($_GET['id']);
		}
	}
	
	private function getSubIdFromGET() {
		if(!isset($_GET['sub_id']))
			return 0;
		else{
			return $this->secureGet($_GET['sub_id']);
		}
	}
	
	private function secureGet($_get_val) {
		$vowels = array("/", "\\", "<", ">", "'", "\"");
		return str_replace($vowels, "", $_get_val);
	}
	// END GET PROCESSING
	
	
	private function getDataFromPOST() {
	}
	
	// SETTERS
	private function setChapter($_chapter) {
		$this->chapter = $_chapter;
	}
	
	private function setAction($_action) {
		$this->action = $_action;
	}
	
	private function setActionInner($_action_inner) {
		$this->action_inner = $_action_inner;
	}
	
	public function setActionInnerByPattern($_pat) {
		$this->action_inner = $this->getActionInnerByPattern($_pat);
	}
	
	private function setId($_id) {
		$this->id = $_id;
	}
	
	private function setSubId($_sub_id) {
		$this->sub_id = $_sub_id;
	}
	
	//private function setUserLogined($_user_logined) {
		//$this->user_logined = $_user_logined;
	//}
	
	
	// GETTERS
	function getChapter() {
		return $this->chapter;
	}
	
	function getAction() {
		return $this->action;
	}
	
	function getActionInner() {
		return $this->action_inner;
	}
	
	function getId() {
		return $this->id;
	}
	
	function getSubId() {
		return $this->sub_id;
	}
}