<?php
class ABTC_Regis{
	const COLLECTION = 'abtc_regis';
	private $_mongo;
	private $_collection;

	public $id = '';
	public $stt = 0;
	public $masohoso = '';
	public $congvanxinphep = array(); //ten, attachments(alias_name, filename, filetype), ngayky.
	public $id_quocgia = array(); //nước cấp thẻ... (Chon nhieu nuoc cung mot luc)
	public $ghichu = '';
	public $giaytolienquan = array();
	public $date_post = '';
	public $status = array(); //1-Đa xu ly, 0 - Chua xu ly
	public $hanxuly = ''; //So ngay xu ly cho khach hang.
	public $ngayxuly = ''; //cap nhat ngay da xu ly.
	public $id_user = '';

	public function __construct(){
		$this->_mongo = DBConnect::init();
		$this->_collection = $this->_mongo->getCollection(ABTC_Regis::COLLECTION);
	}

	public function get_one(){
		return $this->_collection->findOne(array('_id'=> new MongoId($this->id)));
	}
	public function get_one_mshs(){
		return $this->_collection->findOne(array('masohoso'=> $this->masohoso));	
	}
	public function get_all_list(){
		return $this->_collection->find()->sort(array('status'=> 1, 'date_post' => -1));
	}

	public function get_list_condition($condition){
		return $this->_collection->find($condition)->sort(array('_id'=> 1));
	}

	public function insert(){
		$query = array(
			'stt' => intval($this->stt),
			'masohoso' => $this->masohoso,
			'congvanxinphep' => $this->congvanxinphep,
			'id_quocgia' => $this->id_quocgia,
			'ghichu' => $this->ghichu,
			'giaytolienquan' => $this->giaytolienquan,
			'date_post' => new MongoDate(),
			'status' => array($this->status),
			'id_user' => new MongoId($this->id_user));
		return $this->_collection->insert($query);
	}

	public function delete(){
		$query = array('_id' => new MongoId($this->id));
		return $this->_collection->remove($query);
	}

	public function set_status($status){
		$query = array('$set' => array('status' => $status));
		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);	
	}

	public function count_status_0(){
		$query = array('$or'=> array(array('status.0.t' => 0), array('status.0.t' => 1), array('status.0.t' => 2)));
		return $this->_collection->count($query);
	}

	public function check_users($id_user){
		$query = array('id_user'=> new MongoId($id_user));
		$fields = array('_id'=> true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true;
		else return false;
	}
	public function get_maxstt(){
		$query = array('$group' => array( '_id' => '', 'maxstt' => array('$max' => '$stt')));
		$result = $this->_collection->aggregate($query);
		if(isset($result['result'][0]['maxstt']) && $result['result'][0]['maxstt']) return $result['result'][0]['maxstt'] + 1;
		else return 1;
	}

	public function push_tinhtrang(){
		//$query = array('$push' => array('status' => $this->status));
		$query = array('$push' => array('status' => array('$each' => array($this->status), '$position' => 0)));
		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);
	}

	public function pull_status($key){
		$query = array('$unset' => array('status.'.$key => true));
		$condition = array('_id' => new MongoId($this->id));
		$this->_collection->update($condition, $query);
		$query_1 = array('$pull' => array('status' => null));
		return $this->_collection->update($condition, $query_1);
	}
}

?>