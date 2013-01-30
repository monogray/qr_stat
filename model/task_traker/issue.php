<?php
include_once 'model/bd_tables/table_entity.php';
class Issue extends Table_Entity{
	public $table_name = 'issue';
	static public $instance = null;
	
	public $id_order;
	public $id;
	public $summary;
	public $project;
	public $type;
	public $component;
	public $version;
	public $severity;
	public $priority;
	public $status;
	public $progress;
	public $author;
	public $assigned_to;
	public $steps_to_reproduce;
	public $result;
	public $expected_result;
	public $execute_to;
	public $date;
	public $start_date;
	public $update_date;
	public $author_update;
	public $assigned_to_update;
	
	public $project_one_entity;
	public $project_entity;
	
	public $priority_one_entity;
	public $priority_entity;
	
	public $status_one_entity;
	public $status_entity;
	
	public $type_one_entity;
	public $type_entity;
	
	public $users_one_entity;
	public $author_entity;
	public $assigned_to_entity;
	
	public $attach_entity;
	
	public $progress_one_entity;
	public $progress_entity;
	
	function Issue() {
		parent::Table_Entity();
	}
	
	static public function getInstance(){
		if(self::$instance == null){
			self::$instance = new self();
			self::$instance->getMainListFotTracker();
		}
		return self::$instance;
	}

	protected function setValuesByData($_data) {
		include_once 'project.php';
		$this->project_one_entity = Project::getInstance();
		
		include_once 'issue_type.php';
		$this->type_one_entity = IssueType::getInstance();
		
		include_once 'users.php';
		$this->users_one_entity = Users::getInstance();
		
		include_once 'issue_priority.php';
		$this->priority_one_entity = IssuePriority::getInstance();
		
		include_once 'issue_status.php';
		$this->status_one_entity = IssueStatus::getInstance();
		
		include_once 'issue_progress.php';
		$this->progress_one_entity = IssueProgress::getInstance();
		
		include_once 'issue_attach.php';
		
		$this->len = count($_data);
		for ($i = 0; $i < $this->len; $i++) {
			$this->id[$i]					= $_data[$i]['id'];
			$this->id_order[ $this->id[$i] ] = $i;
			$this->summary[$i]				= $_data[$i]['summary'];
			$this->project[$i]				= $_data[$i]['project'];
			$this->type[$i]					= $_data[$i]['type'];
			$this->component[$i]			= $_data[$i]['component'];
			$this->version[$i]				= $_data[$i]['version'];
			$this->severity[$i]				= $_data[$i]['severity'];
			$this->priority[$i]				= $_data[$i]['priority'];
			$this->status[$i]				= $_data[$i]['status'];
			$this->progress[$i]				= $_data[$i]['progress'];
			$this->author[$i]				= $_data[$i]['author'];
			$this->assigned_to[$i]			= $_data[$i]['assigned_to'];
			$this->steps_to_reproduce[$i]	= htmlspecialchars_decode($_data[$i]['steps_to_reproduce']);
			$this->result[$i]				= $_data[$i]['result'];
			$this->expected_result[$i]		= $_data[$i]['expected_result'];
			$this->execute_to[$i]			= $_data[$i]['execute_to'];
			$this->date[$i]					= $_data[$i]['date'];
			$this->start_date[$i]			= $_data[$i]['start_date'];
			$this->update_date[$i]			= $_data[$i]['update_date'];
			$this->author_update[$i]		= $_data[$i]['author_update'];
			$this->assigned_to_update[$i]	= $_data[$i]['assigned_to_update'];
			$this->date[$i]					= $_data[$i]['date'];
			
			$this->project_entity[$i] = new Project();
			$this->project_entity[$i]->setValuesByInstanceAndIdLazy($this->project[$i]);
			
			$this->priority_entity[$i] = new IssuePriority();
			$this->priority_entity[$i]->setValuesByInstanceAndIdLazy($this->priority[$i]);
			
			$this->type_entity[$i] = new IssueType();
			$this->type_entity[$i]->setValuesByInstanceAndIdLazy($this->type[$i]);
			
			$this->status_entity[$i] = new IssueStatus();
			$this->status_entity[$i]->setValuesByInstanceAndIdLazy($this->status[$i]);
			
			$this->author_entity[$i] = new Users();
			$this->author_entity[$i]->setValuesByInstanceAndIdLazy($this->author[$i]);
			
			$this->assigned_to_entity[$i] = new Users();
			$this->assigned_to_entity[$i]->setValuesByInstanceAndIdLazy($this->assigned_to[$i]);
			
			$this->attach_entity[$i] = new Attach();
			$this->attach_entity[$i]->setValuesByInstanceAndIssueIdLazy($this->id[$i]);
			
			$this->progress_entity[$i] = new IssueProgress();
			$this->progress_entity[$i]->setValuesByInstanceAndIdLazy($this->progress[$i]);
		}
	}
	
	
	/**
	 * @param int $_project_id
	 * @return Array(2). Array with two int counts of active issues and closed for current project
	 */
	static public function getIssueCountByProjectId($_project_id) {
		$_instance = self::getInstance();
		$count = Array(0, 0);
		for ($i = 0; $i < $_instance->len; $i++) {
			if($_instance->project_entity[$i]->id[0] == $_project_id && $_instance->status_entity[$i]->id[0] != 4)		// Status not Closed
				$count[0]++;
			else if($_instance->project_entity[$i]->id[0] == $_project_id && $_instance->status_entity[$i]->id[0] == 4)		// Status is Closed
				$count[1]++;
		}
		return $count;
	}
	
	public function getMainList() {
		$this->dat = $this->select_All_default();
		$this->setValuesByData($this->dat);
	}
	
	public function getMainListFotTracker() {
		// Get project by users permitions
		if(Settings::getCurrentUser()->user_rights[0]->edit_all_projects_id[0] == 'all'){
			$_proj_q = 'SELECT id FROM mono_projects';
		}else{
			$_proj_q = 'SELECT id FROM mono_projects WHERE id IN ('.Settings::getCurrentUser()->user_rights[0]->edit_all_projects_id[0].')';
		}
		// Get issue only for permitted projects
		$this->dat = $this->query_to_dat("SELECT * FROM $this->table_name WHERE project IN ($_proj_q) ORDER BY update_date DESC;");
		$this->setValuesByData($this->dat);
	}
	
	public function getOneItem($_id) {
		$this->dat = $this->select_One_by_id($_id);
		$this->setValuesByData($this->dat);
	}
	
	public function createNew() {
		$q ='INSERT INTO '.$this->table_name.'
			(id, summary, author, date) VALUES(
				0,
				"New issue",
				'.Settings::getCurrentUser()->id[0].',
				"'.date("Y-m-d H:i:s").'"
			)';
		$this->run_query($q);

		$this->setInfoMessage('Issue successfully created');
		return mysql_insert_id();
	}
	
	public function updateItem($_id) {
		if( isset($_POST['summary']) && isset($_POST['steps_to_reproduce']) && $_POST['summary'] != ''){
			$_old_issue = new Issue();
			$_old_issue->getOneItem($_id);
			
			$_summary = $_POST['summary'];
			$_project = $_POST['project'];
			$_steps_to_reproduce = htmlspecialchars($_POST['steps_to_reproduce']);
			$_type = $_POST['type'];
			$_assigned_to = $_POST['assigned_to'];
			$_priority = $_POST['priority'];
			$_status = $_POST['status'];
			$_progress = $_POST['progress'];
			
			$q = 'UPDATE '.$this->table_name.' SET
				summary				= "'.$_summary.'",
				project				= "'.$_project.'",
				steps_to_reproduce	= "'.$_steps_to_reproduce.'",
				type				= "'.$_type.'",
				assigned_to			= "'.$_assigned_to.'",
				priority			= "'.$_priority.'",
				status				= "'.$_status.'",
				progress			= "'.$_progress.'",
				update_date			= "'.date("Y-m-d H:i:s").'"
				WHERE id = '.$_id.' LIMIT 1;';
			$this->run_query($q);
			
			// History
			include_once 'issue_history.php';
			$_history = new IssueHistory();
			if($_old_issue->steps_to_reproduce[0] != $_POST['steps_to_reproduce']){
				$_history->addNew($_id, 5, $_old_issue->steps_to_reproduce[0], $_POST['steps_to_reproduce']);
					
				include_once 'model/task_traker/users.php';
				$_user_1 = new Users();
				if(Settings::getCurrentUser()->id[0] != $_old_issue->author[0]){
					$_user_1->getOneItem($_old_issue->author[0]);
				}else{
					$_user_1->mail[0] = '';
				}
					
				$_user_2 = new Users();
				if(Settings::getCurrentUser()->id[0] != $_old_issue->assigned_to[0]){
					$_user_2->getOneItem($_old_issue->assigned_to[0]);
				}else{
					$_user_2->mail[0] = '';
				}
					
				$_mess = 'Task "'.$_old_issue->id[0].'. '.$_old_issue->summary[0].'" changing<br/><br/>';
				$_mess .= 'New description:<br/>';
				$_mess .= $_POST['steps_to_reproduce'];
					
				$this->sendMail($_user_1->mail[0], $_user_2->mail[0], 'Team Manager notification. Task "'.$_old_issue->id[0].'. '.$_old_issue->summary[0].'" changing', $_mess);
			}
		}

		// Attachments
		if(isset($_FILES['attach']) && count($_FILES['attach']['name']) > 0) {
			// Files and images processing
			include_once 'layouts/forms_processing.php';
			$formsProcessing = new FormsProcessing();

			$_files_list = $formsProcessing->FilesProcessing(Settings::$path_to_attachments_dir.'attach/issue/'.$_id.'/', 'attach', '', 50);
			
			include_once 'issue_attach.php';
			$this->attach_one_entity = new Attach();
			
			if($_files_list != ''){
				$_files = explode(";", $_files_list);
				$_len = count($_files);
				for ($i = 0; $i < $_len; $i++) {
					$this->attach_one_entity->createNewByPath($_id, 'attach/issue/'.$_id.'/'.$_files[$i]);
					
					// History
					include_once 'issue_history.php';
					$_history = new IssueHistory();
					$_history->addNew($_id, 6, '', 'attach/issue/'.$_id.'/'.$_files[$i]);
					
					// Need update static variable 'cause instance created befor adding new row in Attaches table, and not be able for next calling
					Attach::updateInstance();
				}
			}
		}
	}
	
	private function sendMail($to_1, $to_2, $theme, $mess) {
		$to = $to_1.', '.$to_2;
	
		$email = 'monogray@ukr.net';
		$headers  = "Content-type: text/html; charset=windows-1251 \r\n";
		$headers .= "From: ".$email."\r\n";
		
		$mess = str_replace("\n", "<br/>", $mess);
		mail($to, $theme , $mess, $headers);
	}
	
	public function drop($_id) {
		parent::drop($_id);
		$this->setInfoMessage('Issue successfully dropped');
	}
	
	public function getTrackerPage() {
		$this->getMainListFotTracker();
	}
	
}