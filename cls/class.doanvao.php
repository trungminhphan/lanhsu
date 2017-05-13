<?php
class DoanVao{
	const COLLECTION = 'doanvao';
	private $_mongo;
	private $_collection;
	public $id = '';
	public $masohoso = '';
	public $congvanxinphep = array(); //id_donvi=array(1,2,3,4), ten, attachments = array(alias_name, filename, filetype), ngayky.
	public $quyetdinhchophep = array(); //id_donvi, ten, attachments = array(alias_name, filename, filetype), ngaybanhanh
	public $quyetdinhchophep_2= array(); //id_donvi, ten, attachments = array(alias_name, filename, filetype), ngaybanhanh
	public $id_dmdoanvao = '';
	public $ngayden = '';
	public $ngaydi = '';
	public $noidung = ''; //noi dung lam viec
	public $id_mucdich = '';
	public $danhsachdoan = array(); //id_canbo, id_donvi, id_chucvu, id_ham
	public $danhsachdoan_2 = array(); //id_canbo, id_donvi, id_chucvu, id_ham (danh sach nay co khi khong co quyet dinh...)
	public $ghichu = '';
	public $date_post = '';
	public $id_user  = '';

	public function __construct(){
		$this->_mongo = DBConnect::init();
		$this->_collection = $this->_mongo->getCollection(DoanVao::COLLECTION);
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
						'quyetdinhchophep_2' => $this->quyetdinhchophep_2,
						'id_dmdoanvao' => new MongoId($this->id_dmdoanvao),
						'ngayden' => $this->ngayden,
						'ngaydi' => $this->ngaydi,
						//'id_donvi' => new MongoId($this->id_donvi),
						'noidung' => $this->noidung,
						'danhsachdoan' => $this->danhsachdoan,
						'danhsachdoan_2' => $this->danhsachdoan_2,
						'id_mucdich' => $this->id_mucdich ? new MongoId($this->id_mucdich) : '',
						'ghichu' => $this->ghichu,
						'date_post' => new MongoDate(),
						'id_user' => new MongoId($this->id_user));
		//logs 
		$logs = new Logs();
		$logs->id = new MongoId();
		$logs->action = 'ADD';
		$logs->collections = 'doanvao';
		$logs->datas = $query;
		$logs->id_user = $this->id_user;
		$logs->insert();
		return $this->_collection->insert($query);
	}

	public function push_danhsachdoan($loaidanhsach){
		$condition = array('_id' => new MongoId($this->id));
		$query = array('$push' => array($loaidanhsach => $this->danhsachdoan));
		return $this->_collection->update($condition, $query);
	}

	public function unset_danhsachdoan($danhsachdoan, $key){
		$condition = array('_id' => new MongoId($this->id));
		$query = array('$unset' => array($danhsachdoan .'.'. $key => true));
		return $this->_collection->update($condition, $query);	
	}

	public function unset_danhsachdoan_all($danhsachdoan){
		$condition = array('_id' => new MongoId($this->id));
		$query = array('$unset' => array($danhsachdoan => true));
		return $this->_collection->update($condition, $query);
	}

	public function pull_danhsachdoan($danhsachdoan, $key){
		$condition = array('_id' => new MongoId($this->id));
		$query = array('$pull' => array($danhsachdoan  => null));
		return $this->_collection->update($condition, $query);	
	}

	public function edit(){
		$query = array('$set' => array(
						'_id' => new MongoId($this->id),
						'congvanxinphep' => $this->congvanxinphep,
						'quyetdinhchophep' => $this->quyetdinhchophep,
						'quyetdinhchophep_2' => $this->quyetdinhchophep_2,
						'id_dmdoanvao' => new MongoId($this->id_dmdoanvao),
						'ngayden' => $this->ngayden,
						'ngaydi' => $this->ngaydi,
						//'id_donvi' => new MongoId($this->id_donvi),
						'noidung' => $this->noidung,
						'danhsachdoan' => $this->danhsachdoan,
						'danhsachdoan_2' => $this->danhsachdoan_2,
						'id_mucdich' => $this->id_mucdich ? new MongoId($this->id_mucdich) : '',
						'ghichu' => $this->ghichu,
						'id_user' => new MongoId($this->id_user)));
		$condition = array('_id' => new MongoId($this->id));
		
		//logs
		$logs = new Logs();
		$logs->id = new MongoId();
		$logs->action = 'EDIT';
		$logs->collections = 'doanvao';
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
		$logs->collections = 'doanvao';
		$logs->datas = $result;
		$logs->id_user = $this->id_user;
		$logs->insert();
		return $this->_collection->remove(array('_id'=> new MongoId($this->id)));
	}

	public function check_dm_dmdoanvao($id_dmdoanvao){
		$query = array('_id' => true);
		$condition = array('id_dmdoanvao' => new MongoId($id_dmdoanvao));
		$result = $this->_collection->findOne($condition, $query);
		if($result['_id']) return true; else return false;
	}

	public function check_dm_donvi($id_donvi){
		$query = array('$or' => array(
							array('congvanxinphep.id_donvi' => new MongoId($id_donvi)),
							array('congvanduocphep.id_donvi' => new MongoId($id_donvi)),
							array('congvanchophep.id_donvi' => new MongoId($id_donvi)),
							array('danhsachdoan.id_donvi' => new MongoId($id_donvi))));
		$fields = array('_id' => true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true;
		else return false;	
	}

	public function check_canbo_donvi($id_canbo, $id_donvi, $id_chucvu){
		$query = array('danhsachdoan.id_canbo' => new MongoId($id_canbo), 'danhsachdoan.id_donvi.0' => $id_donvi[0],'danhsachdoan.id_donvi.1' => $id_donvi[1],'danhsachdoan.id_donvi.2' => $id_donvi[2],'danhsachdoan.id_donvi.3' => $id_donvi[3], 'danhsachdoan.id_chucvu' => $id_chucvu ? new MongoId($id_chucvu) : '');
		$fields = array('_id' => true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true;
		else return false;
	}
	
	public function check_dm_ham($id_ham){
		$query = array('danhsachdoan.id_ham' => new MongoId($id_ham));
		$fields = array('_id' => true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true;
		else return false;
	}
	public function check_dm_mucdich($id_mucdich){
		$query = array('id_mucdich' => new MongoId($id_mucdich));
		$fields = array('_id' => true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true;
		else return false;	
	}

	public function check_dm_chucvu($id_chucvu){
		$query = array('danhsachdoan.id_chucvu' => new MongoId($id_chucvu));
		$fields = array('_id' => true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true;
		else return false;
	}
	
	public function get_union_list($start_date, $end_date){
		$query = array();
		array_push($query, array('ngayden' => array('$gte' => $start_date)));
		array_push($query, array('ngaydi' => array('$lte' => $end_date)));
		if($this->id_dmdoanvao){
			array_push($query, array('id_dmdoanvao' => new MongoId($this->id_dmdoanvao)));
		}
		if($this->id_donvi){
			array_push($query, array('id_donvi' => new MongoId($this->id_donvi)));
		}
		return $this->_collection->find(array('$and' => $query));
	}

	public function convert(){
		$query = array('_id' => new MongoId($this->id),
						'masohoso' => $this->masohoso,
						'congvanxinphep' => $this->congvanxinphep,
						'quyetdinhchophep' => $this->quyetdinhchophep,
						'quyetdinhchophep_2' => $this->quyetdinhchophep_2,
						'id_dmdoanvao' => new MongoId($this->id_dmdoanvao),
						'ngayden' => $this->ngayden,
						'ngaydi' => $this->ngaydi,
						//'id_donvi' => new MongoId($this->id_donvi),
						'noidung' => $this->noidung,
						'danhsachdoan' => $this->danhsachdoan,
						'danhsachdoan_2' => $this->danhsachdoan_2,
						'ghichu' => $this->ghichu,
						'date_post' => new MongoDate(),
						'id_user' => new MongoId($this->id_user));
		//logs 
		$logs = new Logs();
		$logs->id = new MongoId();
		$logs->action = 'ADD';
		$logs->collections = 'doanvao';
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
		$query = array('$or' => array(array('danhsachdoan.id_canbo' => new MongoId($id_canbo)), array('danhsachdoan_2.id_canbo' => new MongoId($id_canbo))));
		$fields = array('_id' => true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true;
		else return false;	
	}

	public function count_soluong($query){
		return $this->_collection->find($query)->count();		
	}
}
?>