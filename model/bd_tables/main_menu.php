<?php
include_once 'table_entity.php';
class MainMenu extends Table_Entity{
	public $table_name = 'main_menu';
	
	static public $instance = null;
	
	public $id_order;		// provides get a serial index by id; id_order[ id ] = i;
	public $id;
	public $name;
	public $chapter;
	public $is_sub_menu;
	public $lang;
	public $order_by;
	public $description;
	public $img_1;
	public $img_2;
	public $meta_keywords;
	public $meta_description;
	public $html_title;
	public $is_visible;
	public $date;

	public $name_by_id;
	
	public $is_current_chapter = -1;		// if false then '-1', if true - id of entity
	
	
	public $entity_sheme_names	= Array('id',				'name',			'chapter',		'is_sub_menu',	'lang',
										'order_by',			'description',	'img_1',		'img_2',		'meta_keywords',
										'meta_description',	'html_title',	'is_visible',	'date');
	
	public $entity_sheme_types	= Array('int',				'text',			'text',			'int',			'int',
										'int',				'text',			'text',			'text',			'text',
										'text',				'text',			'int',			'datetime');
	
	public $entity_sheme_form	= Array('void',				'text',			'text',			'int',			'int',
										'int',				'textarea',		'text',			'text',			'text',
										'text',				'text',			'int',			'info');
	
	public $entity_sheme_descr	= Array('id',				'Название',		'chapter',		'is_sub_menu',	'lang',
										'order_by',			'Описание',		'img_1',		'img_2',		'meta_keywords',
										'meta_description',	'html_title',	'is_visible',	'date');
	
	function MainMenu() {
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
		$this->len = count($_data);
		for ($i = 0; $i < $this->len; $i++) {
			$this->id[$i]					= $_data[$i]['id'];
			$this->id_order[ $this->id[$i] ]	= $i;
			
			$this->name[$i]					= $_data[$i]['name'];
			$this->chapter[$i]				= $_data[$i]['chapter'];
			$this->is_sub_menu[$i]			= $_data[$i]['is_sub_menu'];
			$this->order_by[$i]				= $_data[$i]['order_by'];
			$this->description[$i]			= htmlspecialchars_decode($_data[$i]['description']);
			$this->img_1[$i]				= $_data[$i]['img_1'];
			$this->img_2[$i]				= $_data[$i]['img_2'];
			$this->meta_keywords[$i]		= $_data[$i]['meta_keywords'];
			$this->meta_description[$i]		= $_data[$i]['meta_description'];
			$this->html_title[$i]			= $_data[$i]['html_title'];
			$this->is_visible[$i]			= $_data[$i]['is_visible'];
			$this->date[$i]					= $_data[$i]['date'];
			
			$this->name_by_id[ $this->id[$i] ]	= $_data[$i]['name'];
			
			if(isset($_GET['chapter'])){
				if($_GET['chapter'] == $this->chapter[$i])
					$this->is_current_chapter = $i;
			}else if($this->chapter[$i] == 'index'){
				$this->is_current_chapter = $i;
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
		$this->chapter[0]			= $_instance->chapter[$__id];
		$this->is_sub_menu[0]		= $_instance->is_sub_menu[$__id];
		$this->order_by[0]			= $_instance->order_by[$__id];
		$this->description[0]		= $_instance->description[$__id];
		$this->img_1[0]				= $_instance->img_1[$__id];
		$this->img_2[0]				= $_instance->img_2[$__id];
		$this->meta_keywords[0]		= $_instance->meta_keywords[$__id];
		$this->meta_description[0]	= $_instance->meta_description[$__id];
		$this->html_title[0]		= $_instance->html_title[$__id];
		$this->is_visible[0]		= $_instance->is_visible[$__id];
		$this->date[0]				= $_instance->date[$__id];
		
		$this->is_current_chapter	= $_instance->is_current_chapter;
		
		$this->len = 1;
	}
	
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
					"New Chapter",
					1,
					"'.date("Y-m-d H:i:s").'"
				)';
		$this->run_query($q);

		$this->setInfoMessage('Chapter successfully created');
		return mysql_insert_id();
	}
	
	public function updateItem($_id) {
		$_name			= $_POST['name'];
		$_chapter		= $_POST['chapter'];
		$_description	= htmlspecialchars($_POST['description']);
		
		$q = 'UPDATE '.$this->table_name.' SET
			name = "'.$_name.'",
			description = "'.$_description.'",
			chapter = "'.$_chapter.'"
			WHERE id = '.$_id.' LIMIT 1;';
		
		$this->run_query($q);
		$this->setInfoMessage('Chapter successfully updated');
	}
	
	public function drop($_id) {
		parent::drop($_id);
		$this->setInfoMessage('Chapter successfully dropped');
	}
	
	// Site
	public function getIdCurrentChapter() {
		if($this->is_current_chapter != -1)
			return $this->id[$this->is_current_chapter];
		else
			return null;
	}
}