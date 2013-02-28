<?php
include_once 'table_entity.php';
class Issue extends Table_Entity{
	public $table_name = 'issue';
	
	static public $instance = null;
	
	public $id_order;
	public $id;
	public $name;
	public $summary;
	public $description;
	public $description_2;
	public $menu;
	public $parent_issue_id;
	public $lang;
	public $img_1;
	public $img_2;
	public $img_3;
	public $img_arr;
	public $img_arr_2;
	public $file_arr;
	public $order_by;
	public $css_class;
	public $css_id;
	public $tags;
	public $php_file;
	public $css_file;
	public $is_visible;
	public $type;
	public $properties;
	public $date;
	
	public $img_arr_array	= Array();	// Array of Images pathes. Array[][]
	public $img_arr_array_2	= Array();	// Array of Images pathes. Array[][]
	
	public $menu_entity;
	public $menu_cur_entity;			// MainMenu entity of curent issues parent (if parrent is MainMenu)
	
	public $issue_cur_entity;			// Issue entity of curent issues parent (if parrent is Issue)
	
	public $properties_table;			// Properties of issue
	public $properties_table_data;		// Properties data of issue

	public $name_by_id;
	
	/*public $entity_sheme_names	= Array('id',			'name',				'summary',		'description',	'description_2',
										'menu',			'parent_issue_id',	'lang',			'img_1',		'img_2',			'img_3',
										'img_arr',		'file_arr',			'order_by',		'css_class',	'css_id',
										'tags',			'php_file',			'css_file',		'is_visible',	'properties',
										'date');
	
	public $entity_sheme_types	= Array('int',				'text',			'text',			'int',			'int',
										'int',		'int',	'text',			'text',			'text',			'text',
										'text',				'text',			'int',			'datetime',		'text',
										'text',				'text',			'int',			'datetime',		'text',
										'text');
	
	public $entity_sheme_form	= Array('void',				'text',			'textarea',		'textarea',		'textarea',
										'select',	'void',	'void',			'void',			'void',			'void',
										'img_arr',			'void',			'void',			'void',			'void',
										'void',				'void',			'void',			'void',			'void',
										'void');
	
	public $entity_sheme_descr	= Array('id',				'Название',		'Краткое описание',		'Полное описание',	'Дополнительное описание',
										'Меню',		'Родительская запись',	'lang',			'img_1',				'img_2',			'img_3',
										'img_arr',			'file_arr',		'order_by',				'css_class',		'css_id',
										'tags',				'php_file',		'css_file',				'is_visible',		'properties',
										'date');
	*/
	public $entity_depending	= Array('menu'	=> 'menu_entity');
	public $entity_properties_table	= 'issue_properties';		// Need to define this variable for display properties chapter on view page
	
	function Issue() {
		parent::Table_Entity();
	}
	
	static public function getInstance(){
		if(self::$instance == null){
			self::$instance = new self();
			self::$instance->getMainList();
		}
		return self::$instance;
	}
	
	static public function updateInstance(){
		self::$instance = null;
		self::getInstance();
	}

	protected function setValuesByData($_data) {
		include_once 'main_menu.php';
		$this->menu_entity = MainMenu::getInstance();
		
		include_once 'issue_properties.php';			/// !!!!!!!!!!!!!!!!!!!!!!!!!!!
		$this->properties_table = new IssueProperties();
		$this->properties_table->getMainList();
		
		include_once 'issue_properties_data.php';
		
		$this->len = count($_data);
		for ($i = 0; $i < $this->len; $i++) {
			$this->id[$i]					= $_data[$i]['id'];
			$this->id_order[ $this->id[$i] ]	= $i;
			
			$this->name[$i]					= $_data[$i]['name'];
			$this->summary[$i]				= htmlspecialchars_decode($_data[$i]['summary']);
			$this->description[$i]			= htmlspecialchars_decode($_data[$i]['description']);
			$this->description_2[$i]		= htmlspecialchars_decode($_data[$i]['description_2']);
			$this->menu[$i]					= $_data[$i]['menu'];
			$this->parent_issue_id[$i]		= $_data[$i]['parent_issue_id'];
			$this->lang[$i]					= $_data[$i]['lang'];
			$this->img_1[$i]				= $_data[$i]['img_1'];
			$this->img_2[$i]				= $_data[$i]['img_2'];
			$this->img_3[$i]				= $_data[$i]['img_3'];
			$this->img_arr[$i]				= $_data[$i]['img_arr'];
			$this->img_arr_2[$i]			= $_data[$i]['img_arr_2'];
			$this->file_arr[$i]				= $_data[$i]['file_arr'];
			$this->order_by[$i]				= $_data[$i]['order_by'];
			$this->css_class[$i]			= $_data[$i]['css_class'];
			$this->css_id[$i]				= $_data[$i]['css_id'];
			$this->tags[$i]					= $_data[$i]['tags'];
			$this->php_file[$i]				= $_data[$i]['php_file'];
			$this->css_file[$i]				= $_data[$i]['css_file'];
			$this->is_visible[$i]			= $_data[$i]['is_visible'];
			$this->type[$i]					= $_data[$i]['type'];
			$this->properties[$i]			= $_data[$i]['properties'];
			$this->date[$i]					= $_data[$i]['date'];

			$this->name_by_id[ $this->id[$i] ] = $_data[$i]['name'];
			
			$this->properties_table_data[ $this->id[$i] ] = new IssuePropertiesData();
			$this->properties_table_data[ $this->id[$i] ]->getPropertiesByIssueId($this->id[$i]);
			
			if($this->menu[$i] != -1){
				$this->menu_cur_entity[$i] = new MainMenu();
				$this->menu_cur_entity[$i]->setValuesByInstanceAndId($this->menu[$i]);
			}
			
			if($this->parent_issue_id[$i] != -1){
				$this->issue_cur_entity[$i] = new Issue();
				$this->issue_cur_entity[$i]->setValuesByInstanceAndId($this->parent_issue_id[$i]);
			}
			
			if($this->img_arr[$i] != ''){
				$this->img_arr_array[$i] = explode(";", $this->img_arr[$i]);
			}else{
				$this->img_arr_array[$i] = Array();
			}
			if($this->img_arr_2[$i] != ''){
				$this->img_arr_array_2[$i] = explode(";", $this->img_arr_2[$i]);
			}else{
				$this->img_arr_array_2[$i] = Array();
			}
		}
	}
	
	public function setValuesByInstanceAndId($_id) {
		$_instance = self::getInstance();
		if(isset( $_instance->id_order[$_id] )){
			$__id = $_instance->id_order[$_id];
		}else{
			$__id = 0;
		}
		
		$this->id[0]				= $_instance->id[$__id];
		$this->name[0]				= $_instance->name[$__id];
		$this->summary[0]			= $_instance->summary[$__id];
		$this->description[0]		= $_instance->description[$__id];
		$this->description_2[0]		= $_instance->description_2[$__id];
		$this->menu[0]				= $_instance->menu[$__id];
		$this->parent_issue_id[0]	= $_instance->parent_issue_id[$__id];
		$this->lang[0]				= $_instance->lang[$__id];
		$this->img_1[0]				= $_instance->img_1[$__id];
		$this->img_2[0]				= $_instance->img_2[$__id];
		$this->img_3[0]				= $_instance->img_3[$__id];
		$this->img_arr[0]			= $_instance->img_arr[$__id];
		$this->img_arr_2[0]			= $_instance->img_arr_2[$__id];
		$this->file_arr[0]			= $_instance->file_arr[$__id];
		$this->order_by[0]			= $_instance->order_by[$__id];
		$this->css_class[0]			= $_instance->css_class[$__id];
		$this->css_id[0]			= $_instance->css_id[$__id];
		$this->tags[0]				= $_instance->tags[$__id];
		$this->php_file[0]			= $_instance->php_file[$__id];
		$this->css_file[0]			= $_instance->css_file[$__id];
		$this->is_visible[0]		= $_instance->is_visible[$__id];
		$this->type[0]				= $_instance->type[$__id];
		$this->properties[0]		= $_instance->properties[$__id];
		$this->date[0]				= $_instance->date[$__id];
	
		$this->len = 1;
	}
	
	// Properties processing
	public function getPropertyValue($_issue_id, $_prop_id) {
		if(isset($this->properties_table_data[$_issue_id]->data_value_by_property_id[$_prop_id]))
			return $this->properties_table_data[$_issue_id]->data_value_by_property_id[$_prop_id];
		else
			return '';
	}
	
	public function getPropertyValueByPropertieFieldsName($_issue_id, $_fields_name) {
		$_id = 0;
		for ($i = 0; $i < $this->properties_table->len; $i++) {
			if($this->properties_table->field_name[$i] == $_fields_name)
				$_id = $this->properties_table->id[$i];
		}
		
		if(isset($this->properties_table_data[$_issue_id]->data_value_by_property_id[$_id]))
			return $this->properties_table_data[$_issue_id]->data_value_by_property_id[$_id];
		else
			return '';
	}
	// END Properties processing
	
	public function getMainList() {
		$this->dat = $this->select_All_default();
		$this->setValuesByData($this->dat);
		//$this->dat = $this->query_to_dat("SELECT * FROM $this->table_name ORDER BY date ASC;");
		//$this->setValuesByData($this->dat);
	}
	
	public function getOneItem($_id) {
		$this->dat = $this->select_One_by_id($_id);
		$this->setValuesByData($this->dat);
	}
	
	public function createNew() {
		$q ='INSERT INTO '.$this->table_name.'
				(id, name, lang, date) VALUES(
					0,
					"New issue",
					1,
					"'.date("Y-m-d H:i:s").'"
				)';
		$this->run_query($q);

		$this->setInfoMessage('Issue successfully created');
		return mysql_insert_id();
	}
	
	public function updateItem($_id) {
		$_name				= $_POST['name'];
		$_summary			= htmlspecialchars($_POST['summary']);
		$_description		= htmlspecialchars($_POST['description']);
		$_description_2		= htmlspecialchars($_POST['description_2']);
		$_menu				= $_POST['menu'];
		$_parent_issue_id	= $_POST['parent_issue_id'];
		
		$q = 'UPDATE '.$this->table_name.' SET
			name			= "'.$_name.'",
			summary			= "'.$_summary.'",
			description		= "'.$_description.'",
			description_2	= "'.$_description_2.'",
			menu			= "'.$_menu.'",
			parent_issue_id	= "'.$_parent_issue_id.'"
			WHERE id = '.$_id.' LIMIT 1;';
		
		$this->run_query($q);
		
		// Attachments
		$this->updateAttachments($_id, 'img_arr');
		$this->updateAttachments($_id, 'img_arr_2');

		$this->propertiesUpdate($_id);		
		$this->setInfoMessage('Issue successfully updated');
	}
	
	// Saas
	public function updateItem_portfolio($_id) {
		$_name				= $_POST['name'];
		$_summary			= htmlspecialchars($_POST['summary']);
		$q = 'UPDATE '.$this->table_name.' SET
			name			= "'.$_name.'",
			summary			= "'.$_summary.'"
			WHERE id = '.$_id.' LIMIT 1;';
		
		$this->run_query($q);
		
		// Attachments
		$this->updateAttachments($_id, 'img_arr');
	}
	
	public function updateItem_text_block($_id) {
		$_name				= $_POST['name'];
		$_summary			= htmlspecialchars($_POST['summary']);
		$q = 'UPDATE '.$this->table_name.' SET
			name			= "'.$_name.'",
			summary			= "'.$_summary.'"
			WHERE id = '.$_id.' LIMIT 1;';
	
		$this->run_query($q);
	
		// Attachments
		$this->updateAttachments($_id, 'img_arr');
	}
	
	public function createNewByChapterIdAndType($_main_menu_id, $_type) {
		$q ='INSERT INTO '.$this->table_name.'
				(id, name, lang, menu, type, date) VALUES(
					0,
					"New issue",
					1,
					'.$_main_menu_id.',
					'.$_type.',
					"'.date("Y-m-d H:i:s").'"
				)';
		$this->run_query($q);
	
		$this->setInfoMessage('Issue successfully created');
		return mysql_insert_id();
	}
	
	public function updateAttachments($_issue_id, $_name) {
		if(isset($_FILES[$_name]) && count($_FILES[$_name]['name']) > 0 && $_FILES[$_name]['name'][0] != '') {
			$_path = '';
			if($_name == 'img_arr')
				$_path = Settings::$path_to_attachments_dir.'issue/'.$_issue_id.'/';
			else if($_name == 'img_arr_2')
				$_path = Settings::$path_to_attachments_dir.'issue/'.$_issue_id.'/img_arr_2/';

			// Files and images processing
			include_once 'layouts/forms_processing.php';
			$formsProcessing = new FormsProcessing();
		
			$_files_list = $formsProcessing->FilesProcessing($_path, $_name, '', 20);
				
			// Get current filles
			$_file_arr_str = $this->getFilesListByIssueId($_issue_id, $_name);
			// Concatenate old files and new one
			if($_file_arr_str == '')
				$_file_arr_fin = $_files_list;
			else
				$_file_arr_fin = $_file_arr_str.';'.$_files_list;
				
			$q = 'UPDATE '.$this->table_name.' SET
				'.$_name.'	= "'.$_file_arr_fin.'"
				WHERE id = '.$_issue_id.' LIMIT 1;';
			$this->run_query($q);
		}
	}
	
	public function getFilesListByIssueId($_id, $_file_arr_name) {
		$_issue = self::getInstance();
		if($_file_arr_name == 'img_arr')
			return $_issue->img_arr[ $_issue->id_order[ $_id ] ];
		else if($_file_arr_name == 'img_arr_2')
			return $_issue->img_arr_2[ $_issue->id_order[ $_id ] ];
	}
	
	/**
	 * Update properties in '<b>issue_properties_data</b>'
	 */
	public function propertiesUpdate($_id) {
		$tmp_entity = new Issue();
		$tmp_entity->getOneItem($_id);
		
		$_properties_table = $tmp_entity->properties_table;
		for ($i = 0; $i < $_properties_table->len; $i++) {
			if($_properties_table->type[$i] == 'text'){
				$_data_val = $_POST[$_properties_table->field_name[$i]];
				$_current_val = $tmp_entity->getPropertyValue($tmp_entity->id[0], $_properties_table->id[$i]);
				$tmp_entity->properties_table_data[$tmp_entity->id[0]]->updateOnePropety($tmp_entity->id[0], $_properties_table->id[$i], $_data_val);
			}
		}
	}
	
	public function drop($_id) {
		parent::drop($_id);
		
		// Delete all related rows from issue_properties_data
		$q ='DELETE FROM '.$this->table_name.'_data WHERE issue_id = '.$_id.';';
		$this->run_query($q);
		
		$this->setInfoMessage('Issue successfully dropped');
	}
	
	public function dropImgArr($_id, $_img_arr_id, $_img_arr_name) {
		$_issue = new Issue();
		$_issue->getOneItem($_id);
		
		$_path = '';
		$_file_arr = Array();
		if($_img_arr_name == 'img_arr'){
			$_file_arr = $_issue->img_arr_array[0];
			$_path = Settings::$path_to_attachments_dir.'issue/'.$_id.'/'.$_file_arr[$_img_arr_id];
		}else if($_img_arr_name == 'img_arr_2'){
			$_file_arr = $_issue->img_arr_array_2[0];
			$_path = Settings::$path_to_attachments_dir.'issue/'.$_id.'/img_arr_2/'.$_file_arr[$_img_arr_id];
		}
		
		unlink($_path);
		
		unset($_file_arr[$_img_arr_id]);
		
		$_img_arr_fin = implode(";", $_file_arr);
		
		$q = 'UPDATE '.$this->table_name.' SET
				'.$_img_arr_name.'	= "'.$_img_arr_fin.'"
				WHERE id = '.$_id.' LIMIT 1;';
		$this->run_query($q);
	}
	
	public function getListByChapterId($_main_menu_id) {
		$q ='SELECT * FROM '.$this->table_name.' WHERE menu = '.$_main_menu_id.' ORDER BY date DESC;';
		$this->dat = $this->query_to_dat($q);
		$this->setValuesByData($this->dat);
	}
	
	public function getListByParentIssueId($_issue_id) {
		$q ='SELECT * FROM '.$this->table_name.' WHERE parent_issue_id = '.$_issue_id.';';
		$this->dat = $this->query_to_dat($q);
		$this->setValuesByData($this->dat);
	}
	
	public function createNewByChapterId($_main_menu_id) {
		$q ='INSERT INTO '.$this->table_name.'
				(id, name, lang, menu, date) VALUES(
					0,
					"New issue",
					1,
					'.$_main_menu_id.',
					"'.date("Y-m-d H:i:s").'"
				)';
		$this->run_query($q);
		
		$this->setInfoMessage('Issue successfully created');
		return mysql_insert_id();
	}
	
	public function createNewByParentIssueChapterId($_issue_id) {
		$q ='INSERT INTO '.$this->table_name.'
				(id, name, lang, menu, parent_issue_id, date) VALUES(
					0,
					"New issue",
					1,
					-1,
					'.$_issue_id.',
					"'.date("Y-m-d H:i:s").'"
				)';
		$this->run_query($q);
	
		$this->setInfoMessage('Issue successfully created');
		return mysql_insert_id();
	}
}