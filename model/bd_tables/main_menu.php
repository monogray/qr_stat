<?php
include_once 'table_entity.php';
class MainMenu extends Table_Entity{
	public $table_name = 'main_menu';
	
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

	protected function setValuesByData($_data) {
		$this->len = count($_data);
		for ($i = 0; $i < $this->len; $i++) {
			$this->id[$i]					= $_data[$i]['id'];
			$this->id_order[$this->id[$i]]	= $i;
			
			$this->name[$i]					= $_data[$i]['name'];
			$this->chapter[$i]				= $_data[$i]['chapter'];
			$this->is_sub_menu[$i]			= $_data[$i]['is_sub_menu'];
			$this->order_by[$i]				= $_data[$i]['order_by'];
			$this->description[$i]			= $_data[$i]['description'];
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
		$_name = $_POST['name'];
		$_description = $_POST['description'];
		$_chapter = $_POST['chapter'];
		
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