<?php
include_once 'table_entity.php';
class Issue extends Table_Entity{
	public $table_name = 'issue';
	
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
	public $file_arr;
	public $order_by;
	public $css_class;
	public $css_id;
	public $tags;
	public $php_file;
	public $css_file;
	public $is_visible;
	public $properties;
	public $date;
	
	public $menu_entity;
	
	public $properties_table;			// Properties of issue
	public $properties_table_data;		// Properties data of issue

	public $name_by_id;
	
	public $entity_sheme_names	= Array('id',			'name',				'summary',		'description',	'description_2',
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
	
	public $entity_depending	= Array('menu'	=> 'menu_entity');
	public $entity_properties_table	= 'issue_properties';		// Need to define this variable for display properties chapter on view page
	
	function Issue() {
		parent::Table_Entity();
	}

	protected function setValuesByData($_data) {
		include_once 'main_menu.php';
		$this->menu_entity = new MainMenu();
		$this->menu_entity->getMainList();
		
		include_once 'issue_properties.php';
		$this->properties_table = new IssueProperties();
		$this->properties_table->getMainList();
		
		include_once 'issue_properties_data.php';
		
		$this->len = count($_data);
		for ($i = 0; $i < $this->len; $i++) {
			$this->id_order[$i]				= $i;
			$this->id[$i]					= $_data[$i]['id'];
			$this->name[$i]					= $_data[$i]['name'];
			$this->summary[$i]				= $_data[$i]['summary'];
			$this->description[$i]			= $_data[$i]['description'];
			$this->description_2[$i]		= $_data[$i]['description_2'];
			$this->menu[$i]					= $_data[$i]['menu'];
			$this->parent_issue_id[$i]		= $_data[$i]['parent_issue_id'];
			$this->lang[$i]					= $_data[$i]['lang'];
			$this->img_1[$i]				= $_data[$i]['img_1'];
			$this->img_2[$i]				= $_data[$i]['img_2'];
			$this->img_3[$i]				= $_data[$i]['img_3'];
			$this->img_arr[$i]				= $_data[$i]['img_arr'];
			$this->file_arr[$i]				= $_data[$i]['file_arr'];
			$this->order_by[$i]				= $_data[$i]['order_by'];
			$this->css_class[$i]			= $_data[$i]['css_class'];
			$this->css_id[$i]				= $_data[$i]['css_id'];
			$this->tags[$i]					= $_data[$i]['tags'];
			$this->php_file[$i]				= $_data[$i]['php_file'];
			$this->css_file[$i]				= $_data[$i]['css_file'];
			$this->is_visible[$i]			= $_data[$i]['is_visible'];
			$this->properties[$i]			= $_data[$i]['properties'];
			$this->date[$i]					= $_data[$i]['date'];

			$this->name_by_id[ $this->id[$i] ] = $_data[$i]['name'];
			
			$this->properties_table_data[ $this->id[$i] ] = new IssuePropertiesData();
			$this->properties_table_data[ $this->id[$i] ]->getPropertiesByIssueId($this->id[$i]);
		}
	}
	
	// Properties processing
	public function getPropertyValue($_issue_id, $_prop_id) {
		if(isset($this->properties_table_data[$_issue_id]->data_value_by_property_id[$_prop_id]))
			return $this->properties_table_data[$_issue_id]->data_value_by_property_id[$_prop_id];
		else
			return '';
	}
	// END Properties processing
	
	public function getMainList() {
		$this->dat = $this->select_All_default();
		$this->setValuesByData($this->dat);
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
		$_name = $_POST['name'];
		$_summary = $_POST['summary'];
		$_description = $_POST['description'];
		$_description_2 = $_POST['description_2'];
		$_menu = $_POST['menu'];
		
		$q = 'UPDATE '.$this->table_name.' SET
			name			= "'.$_name.'",
			summary			= "'.$_summary.'",
			description		= "'.$_description.'",
			description_2	= "'.$_description_2.'",
			menu			= "'.$_menu.'"
			WHERE id = '.$_id.' LIMIT 1;';
		
		$this->run_query($q);

		$this->propertiesUpdate($_id);		
		$this->setInfoMessage('Issue successfully updated');
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
	
	public function getListByChapterId($_main_menu_id) {
		$q ='SELECT * FROM '.$this->table_name.' WHERE menu = '.$_main_menu_id.';';
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