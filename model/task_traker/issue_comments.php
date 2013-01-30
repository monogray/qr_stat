<?php
include_once 'model/bd_tables/table_entity.php';
class IssueComments extends Table_Entity{
	public $table_name = 'issue_comments';
	
	public $id_order;
	public $id;
	public $comment;
	public $author;
	public $issue;
	public $date;
	
	public $author_entity;
	
	function IssueComments() {
		parent::Table_Entity();
	}

	protected function setValuesByData($_data) {
		$this->len = count($_data);
		for ($i = 0; $i < $this->len; $i++) {
			$this->id[$i]			= $_data[$i]['id'];
			$this->id_order[ $this->id[$i] ] = $i;
			$this->comment[$i]		= str_replace("\n", "<br/>", htmlspecialchars_decode($_data[$i]['comment']));
			$this->author[$i]		= $_data[$i]['author'];
			$this->issue[$i]		= $_data[$i]['issue'];
			$this->date[$i]			= $_data[$i]['date'];
			
			$this->author_entity[$i] = new Users();
			$this->author_entity[$i]->setValuesByInstanceAndIdLazy($this->author[$i]);
		}
	}
	
	public function getMainListByIssueId($_id) {
		$this->dat = $this->query_to_dat("SELECT * FROM $this->table_name WHERE issue=$_id ORDER BY date DESC;");
		$this->setValuesByData($this->dat);
	}
	
	public function getMainList() {
		$this->dat = $this->select_All_default();
		$this->setValuesByData($this->dat);
	}
	
	public function getOneItem($_id) {
		$this->dat = $this->select_One_by_id($_id);
		$this->setValuesByData($this->dat);
	}
	
	public function createByIssueId($_issue_id) {
		$_comment = htmlspecialchars($_POST['comment']);
		
		$q ='INSERT INTO '.$this->table_name.'
		 (id, comment, author, issue, date) VALUES(
	 		0,
	 		"'.$_comment.'",
	 		'.Settings::getCurrentUser()->id[0].',
	 		'.$_issue_id.',
	 		"'.date("Y-m-d H:i:s").'"
		 )';
		$this->run_query($q);
	
		$this->setInfoMessage('Comment successfully added');
		
		// Send mail
		include_once 'utils/mono_utils.php';
		include_once 'issue.php';
		include_once 'model/task_traker/users.php';
		
		$_cur_issue = new Issue();
		$_cur_issue->getOneItem($_issue_id);
		
		$_user_1 = new Users();
		if(Settings::getCurrentUser()->id[0] != $_cur_issue->author[0]){
			$_user_1->getOneItem($_cur_issue->author[0]);
		}else{
			$_user_1->mail[0] = '';
		}
			
		$_user_2 = new Users();
		if(Settings::getCurrentUser()->id[0] != $_cur_issue->assigned_to[0]){
			$_user_2->getOneItem($_cur_issue->assigned_to[0]);
		}else{
			$_user_2->mail[0] = '';
		}
			
		$_mess = 'In task "'.$_cur_issue->id[0].'. '.$_cur_issue->summary[0].'" added new comment<br/><br/>';
		$_mess .= 'Comment:<br/><br/>';
		$_mess .= $_POST['comment'];
		
		MonoUtils::sendMail($_user_1->mail[0], $_user_2->mail[0], 'Team Manager notification. In task "'.$_cur_issue->id[0].'. '.$_cur_issue->summary[0].'" added new comment', $_mess);
		
		return mysql_insert_id();
	}
	
	public function createNew() {
	}
	
	public function updateItem($_id) {
	}
	
	public function drop($_id) {
		parent::drop($_id);
		$this->setInfoMessage('Issue successfully dropped');
	}
}