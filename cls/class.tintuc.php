<?php
class TinTuc{
	const COLLECTION = 'tintuc';
	private $_mongo;
	private $_collection;

	public $id = '';
	public $tieude = '';
	public $mota = '';
	public $noidung = '';
	public $hinhanh = array();
	public $date_post = '';
	public $id_user = '';

	public function __construct(){
		$this->_mongo = DBConnect::init();
		$this->_collection = $this->_mongo->getCollection(TinTuc::COLLECTION);
	}

	public function get_all_list(){
		return $this->_collection->find()->sort(array('date_post' => -1));
	}

	public function get_list_condition($condition){
		return $this->_collection->find($condition)->sort(array('date_post' => -1));
	}

	public function get_relate_list(){
		$query = array('_id' => array('$ne' => new MongoId($this->id)));
		return $this->_collection->find($query)->sort(array('date_post' => -1))->limit(5);
	}

	public function get_one(){
		$query = array('_id' => new MongoId($this->id));
		return $this->_collection->findOne($query);
	}

	public function insert(){
		$query = array(
			'tieude' => $this->tieude,
			'mota' => $this->mota,
			'noidung' => $this->noidung,
			'hinhanh' => $this->hinhanh,
			'date_post' => new MongoDate(),
			'id_user' => new MongoId($this->id_user)
		);
		return $this->_collection->insert($query);
	}

	public function edit(){
		$query = array('$set' => array(
			'tieude' => $this->tieude,
			'mota' => $this->mota,
			'noidung' => $this->noidung,
			'hinhanh' => $this->hinhanh,
		));
		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);
	} 

	public function delete(){
		$query = array('_id' => new MongoId($this->id));
		return $this->_collection->remove($query);
	}

}
?>