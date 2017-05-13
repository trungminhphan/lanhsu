<?php
class ABTC{
	const COLLECTION = 'abtc';
	private $_mongo;
	private $_collection;
	public $id = '';
	public $masohoso = '';
	public $congvanxinphep = array(); //id_donvi=array(1,2,3,4), ten, attachments = array(alias_name, filename, filetype), ngayky.
	public $quyetdinhchophep = array(); //id_donvi, ten, attachments = array(alias_name, filename, filetype), ngaybanhanh
	public $chophep = 0;
	public $id_quocgia = array(); //nước cấp thẻ... (Chon nhieu nuoc cung mot luc)
	public $thongtinthanhvien = array(); //id_canbo, id_donvi, id_chucvu, tinhtrang (0/ Cap moi  1/ gia han)  sothe ngaycap, ngayhethan
	public $giaytolienquan = array();
	public $ghichu = '';
	public $date_post = '';
	public $id_user  = '';

	public function __construct(){
		$this->_mongo = DBConnect::init();
		$this->_collection = $this->_mongo->getCollection(ABTC::COLLECTION);
	}
	public function get_one(){
		return $this->_collection->findOne(array('_id'=> new MongoId($this->id)));
	}
	public function get_all_list(){
		return $this->_collection->find()->sort(array('_id'=> 1));
	}
	public function get_list_condition($condition){
		return $this->_collection->find($condition)->sort(array('_id'=> 1));
	}

	public function insert(){
		$query = array('_id' => new MongoId($this->id),
					'congvanxinphep' => $this->congvanxinphep,
					'quyetdinhchophep' => $this->quyetdinhchophep,
					'chophep' => $this->chophep,
					'id_quocgia' => $this->id_quocgia,
					'thongtinthanhvien' => $this->thongtinthanhvien,
					'ghichu' => $this->ghichu,
					'giaytolienquan' => $this->giaytolienquan,
					'date_post' => new MongoId(),
					'id_user' => new MongoId($this->id_user));
		//logs 
		$logs = new Logs();
		$logs->id = new MongoId();
		$logs->action = 'ADD';
		$logs->collections = 'abtc';
		$logs->datas = $query;
		$logs->id_user = $this->id_user;
		$logs->insert();
		return $this->_collection->insert($query);
	}

	public function edit(){
		$query = array('$set' => array(
					'_id' => new MongoId($this->id),
					'congvanxinphep' => $this->congvanxinphep,
					'quyetdinhchophep' => $this->quyetdinhchophep,
					'chophep' => $this->chophep,
					'id_quocgia' => $this->id_quocgia,
					'thongtinthanhvien' => $this->thongtinthanhvien,
					'giaytolienquan' => $this->giaytolienquan,
					'ghichu' => $this->ghichu),
					'id_user' => new MongoId($this->id_user));
		$condition = array('_id' => new MongoId($this->id));
		//logs 
		$logs = new Logs();
		$logs->id = new MongoId();
		$logs->action = 'EDIT';
		$logs->collections = 'abtc';
		$logs->datas = $query['$set'];
		$logs->id_user = $this->id_user;
		$logs->insert();

		return $this->_collection->update($condition, $query);
	}

	public function delete(){
		$result = $this->_collection->findOne(array('_id'=> new MongoId($this->id)));
		//logs
		$logs = new Logs();
		$logs->id = new MongoId();
		$logs->action = 'DELETE';
		$logs->collections = 'doanra';
		$logs->datas = $result;
		$logs->id_user = $this->id_user;
		$logs->insert();
		return $this->_collection->remove(array('_id'=> new MongoId($this->id)));
	}

	public function check_donvi_chucvu($id_canbo, $id_donvi, $id_chucvu){
		$query = array('$and'=>array(
						array('thongtinthanhvien.id_canbo' => new MongoId($id_canbo)), 
						array('thongtinthanhvien.id_donvi' => new MongoId($id_donvi)), 
						array('thongtinthanhvien.id_chucvu' => new MongoId($id_chucvu))));
		$fields = array('_id' => true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true;
		else return false;
	}

	public function check_dm_quocgia($id_quocgia){
		$query = array('id_quocgia' => new MongoId($id_quocgia));
		$fields = array('_id' => true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true;
		else return false;	
	}

	public function check_dm_donvi($id_donvi){
		$query = array('thongtinthanhvien.id_donvi' => new MongoId($id_donvi));
		/*$query = array('$or' => array(
							array('id_donvi' => new MongoId($id_donvi)),
							array('thongtinthanhvien.id_donvi' => new MongoId($id_donvi))));*/
		$fields = array('_id' => true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true;
		else return false;	
	}

	public function check_dm_chucvu($id_chucvu){
		$query = array('thongtinthanhvien.id_chucvu' => new MongoId($id_chucvu));
		$fields = array('_id' => true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true;
		else return false;	
	}

	public function check_dm_canbo($id_canbo){
		$query = array('thongtinthanhvien.id_canbo' => new MongoId($id_canbo));
		$fields = array('_id' => true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true;
		else return false;	
	}

	public function get_union_list($start_date, $end_date){
		$query = array();
		array_push($query, array('quyetdinhchophep.ngaybanhanh' => array('$gte' => $start_date)));
		array_push($query, array('quyetdinhchophep.ngaybanhanh' => array('$lte' => $end_date)));
		return $this->_collection->find(array('$and' => $query));
	}

	public function convert(){
		$query = array('_id' => new MongoId($this->id),
					'masohoso' => $this->masohoso,
					'congvanxinphep' => $this->congvanxinphep,
					'quyetdinhchophep' => $this->quyetdinhchophep,
					'chophep' => $this->chophep,
					'id_quocgia' => $this->id_quocgia,
					'thongtinthanhvien' => $this->thongtinthanhvien,
					'ghichu' => $this->ghichu,
					'date_post' => new MongoId(),
					'id_user' => new MongoId($this->id_user));
		//logs 
		$logs = new Logs();
		$logs->id = new MongoId();
		$logs->action = 'ADD';
		$logs->collections = 'abtc';
		$logs->datas = $query;
		$logs->id_user = $this->id_user;
		$logs->insert();
		
		return $this->_collection->insert($query);
	}

	public function count_to_user(){
		$query = array('id_user' => new MongoId($this->id_user));
		return $this->_collection->count($query);
	}

	public function check_timkiem($id_canbo){
		$query = array('thongtinthanhvien.id_canbo' => new MongoId($id_canbo));
		$fields = array('_id' => true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true;
		else return false;	
	}
}
?>