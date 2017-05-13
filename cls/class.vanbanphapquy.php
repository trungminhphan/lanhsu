<?php
class VanBanPhapQuy{
	const COLLECTION = 'vanbanphapquy';
	public $id = '';
	public $id_linhvuc = '';
	public $loaivanban = ''; //xuat canh, nhap canh, ABTC
	public $id_coquanbanhanh = ''; //donvi...
	public $sovanban = '';
	public $trichyeu = '';
	public $ngaybanhanh = '';
	public $dinhkem = array();
	public $date_post = '';
	public $id_user = '';

	public function __construct(){
		$this->_mongo = DBConnect::init();
		$this->_collection = $this->_mongo->getCollection(VanBanPhapQuy::COLLECTION);
	}

	public function get_one(){
		return $this->_collection->findOne(array('_id'=> new MongoId($this->id)));
	}

	public function get_all_list(){
		return $this->_collection->find()->sort(array('date_post'=> -1));
	}

	public function get_list_condition($condition){
		return $this->_collection->find($condition)->sort(array('date_post'=> -1));
	}
	public function get_list_limit($limit){
		return $this->_collection->find($condition)->sort(array('date_post'=> -1))->limit($limit);	
	}

	public function insert(){
		$query = array(
			'id_linhvuc' => $this->id_linhvuc ? new MongoId($this->id_linhvuc) : '',
			'loaivanban' => $this->loaivanban,
			'id_coquanbanhanh' => $this->id_coquanbanhanh ? new MongoId($this->id_coquanbanhanh) : '',
			'sovanban' => $this->sovanban,
			'trichyeu' => $this->trichyeu,
			'ngaybanhanh' => $this->ngaybanhanh,
			'dinhkem' => $this->dinhkem,
			'date_post' => new MongoDate(),
			'id_user' => $this->id_user ? new MongoId($this->id_user) : ''
		);
		return $this->_collection->insert($query);
	}

	public function edit(){
		$query = array( '$set' => array(
			'id_linhvuc' => $this->id_linhvuc ? new MongoId($this->id_linhvuc) : '',
			'loaivanban' => $this->loaivanban,
			'id_coquanbanhanh' => $this->id_coquanbanhanh ? new MongoId($this->id_coquanbanhanh) : '',
			'sovanban' => $this->sovanban,
			'trichyeu' => $this->trichyeu,
			'ngaybanhanh' => $this->ngaybanhanh,
			'dinhkem' => $this->dinhkem,
			'id_user' => $this->id_user ? new MongoId($this->id_user) : ''
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