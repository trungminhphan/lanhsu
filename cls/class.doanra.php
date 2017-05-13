<?php
class DoanRa{
	const COLLECTION = 'doanra';
	private $_mongo;
	private $_collection;
	public $id = '';
	public $congvanxinphep = array(); //id_donvi=array(1,2,3,4), ten, attachments = array(alias_name, filename, filetype), ngayky.
	public $quyetdinhchophep = array(); //id_donvi, ten, attachments = array(alias_name, filename, filetype), ngaybanhanh
	public $quyetdinhchophep_2= array(); //id_donvi, ten, attachments = array(alias_name, filename, filetype), ngaybanhanh
	public $ngaydi = '';
	public $ngayve = '';
	public $songay = 0;
	public $id_donvimoi = array();
	public $id_quocgia = array(); //Nước đến.
	public $id_mucdich = ''; //mục đích chuyến đi
	public $id_kinhphi = ''; //kinh phi cho chuyen di
	public $sotien = array(); // donvitien, sotien, tygia, VND
	public $danhsachdoan = array(); //id_canbo, id_donvi, id_chucvu, id_ham
	public $danhsachdoan_2 = array(); //id_canbo, id_donvi, id_chucvu, id_ham
	public $noidung = '';
	public $baocao = array(); //noidung, attachments = array(alias_name, filename, filetype)
	public $ghichu = '';
	public $date_post = '';
	public $id_user  = '';

	public function __construct(){
		$this->_mongo = DBConnect::init();
		$this->_collection = $this->_mongo->getCollection(DoanRa::COLLECTION);
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
		$query = array(
			'_id' => new MongoId($this->id),
			'congvanxinphep' => $this->congvanxinphep,
			'quyetdinhchophep' => $this->quyetdinhchophep,
			'quyetdinhchophep_2' => $this->quyetdinhchophep_2,
			'ngaydi' => $this->ngaydi,
			'ngayve' => $this->ngayve,
			'songay' => $this->songay,
			'id_donvimoi' => $this->id_donvimoi,
			'id_quocgia' => $this->id_quocgia,
			'id_mucdich' => $this->id_mucdich ? new MongoId($this->id_mucdich) : '',
			'id_kinhphi' => $this->id_kinhphi ? new MongoId($this->id_kinhphi) : '',
			'sotien' => $this->sotien,
			'danhsachdoan' => $this->danhsachdoan,
			'danhsachdoan_2' => $this->danhsachdoan_2,
			'noidung' => $this->noidung,
			'baocao' => $this->baocao,
			'ghichu' => $this->ghichu,
			'date_post' => new MongoDate(),
			'id_user' => new MongoId($this->id_user)
			);
		//logs 
		$logs = new Logs();
		$logs->id = new MongoId();
		$logs->action = 'ADD';
		$logs->collections = 'doanra';
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
							'quyetdinhchophep_2' => $this->quyetdinhchophep_2,
							'ngaydi' => $this->ngaydi,
							'ngayve' => $this->ngayve,
							'songay' => $this->songay,
							'id_donvimoi' => $this->id_donvimoi,
							'id_quocgia' => $this->id_quocgia,
							'id_mucdich' => $this->id_mucdich ? new MongoId($this->id_mucdich) : '',
							'id_kinhphi' => $this->id_kinhphi ? new MongoId($this->id_kinhphi) : '',
							'sotien' => $this->sotien,
							'danhsachdoan' => $this->danhsachdoan,
							'danhsachdoan_2' => $this->danhsachdoan_2,
							'noidung' => $this->noidung,
							'baocao' => $this->baocao,
							'ghichu' => $this->ghichu,
							'id_user' => new MongoId($this->id_user)));
		$condition = array('_id' => new MongoId($this->id));
		//logs 
		$logs = new Logs();
		$logs->id = new MongoId();
		$logs->action = 'EDIT';
		$logs->collections = 'doanra';
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
		$arr_donvi = explode(",", $id_donvi);
		$query = array('$and'=>array(
						array('danhsachdoan.id_canbo' => new MongoId($id_canbo)), 
						array('danhsachdoan.id_donvi.0' => $arr_donvi[0]),
						array('danhsachdoan.id_donvi.1' => $arr_donvi[1]),
						array('danhsachdoan.id_donvi.2' => $arr_donvi[2]),
						array('danhsachdoan.id_donvi.3' => $arr_donvi[3]),
						array('danhsachdoan.id_chucvu' => $id_chucvu ? new MongoId($id_chucvu) : '')));
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
		$query = array('$or' => array(
							array('congvanxinphep.id_donvi' => new MongoId($id_donvi)),
							array('quyetdinhchophep.id_donvi' => new MongoId($id_donvi)),
							array('danhsachdoan.id_donvi' => new MongoId($id_donvi)),
							array('id_donvimoi' => $this->id_donvimoi ? new MongoId($this->id_donvimoi) : '')));
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

	public function check_dm_ham($id_ham){
		$query = array('danhsachdoan.id_ham' => new MongoId($id_ham));
		$fields = array('_id' => true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true;
		else return false;
	}

	public function check_dm_canbo($id_canbo){
		$query = array('danhsachdoan.id_canbo' => new MongoId($id_canbo));
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

	public function check_dm_mucdich($id_mucdich){
		$query = array('id_mucdich' => new MongoId($id_mucdich));
		$fields = array('_id' => true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true;
		else return false;	
	}

	public function check_dm_kinhphi($id_kinhphi){
		$query = array('id_kinhphi' => new MongoId($id_kinhphi));
		$fields = array('_id' => true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true;
		else return false;	
	}

	/*public function get_union_list($start_date, $end_date){
		$query = array();
		array_push($query, array('ngaydi' => array('$gte' => $start_date)));
		array_push($query, array('ngayve' => array('$lte' => $end_date)));
		if($this->id_kinhphi){
			array_push($query, array('id_kinhphi' => new MongoId($this->id_kinhphi)));
		}
		return $this->_collection->find(array('$and' => $query));
	}*/

	public function convert(){
		$query = array(
			'_id' => new MongoId($this->id),
			'masohoso' => $this->masohoso,
			'congvanxinphep' => $this->congvanxinphep,
			'quyetdinhchophep' => $this->quyetdinhchophep,
			'quyetdinhchophep_2' => $this->quyetdinhchophep_2,
			'ngaydi' => $this->ngaydi,
			'ngayve' => $this->ngayve,
			'songay' => $this->songay,
			'id_donvimoi' => $this->id_donvimoi,
			'id_quocgia' => $this->id_quocgia,
			'id_mucdich' => $this->id_mucdich ? new MongoId($this->id_mucdich) : '',
			'id_kinhphi' => $this->id_kinhphi ? new MongoId($this->id_kinhphi) : '',
			'sotien' => $this->sotien,
			'danhsachdoan' => $this->danhsachdoan,
			'danhsachdoan_2' => $this->danhsachdoan_2,
			'noidung' => $this->noidung,
			'baocao' => $this->baocao,
			'ghichu' => $this->ghichu,
			'date_post' => new MongoDate(),
			'id_user' => new MongoId($this->id_user)
		);
		//logs 
		$logs = new Logs();
		$logs->id = new MongoId();
		$logs->action = 'ADD';
		$logs->collections = 'doanra';
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

	public function count_sodoan($query){
		return $this->_collection->find($query)->count();
	}

	public function count_soluot($query, $id_donvi){
		$union_list = $this->_collection->find($query);
		$c1 = 0;
		if($union_list){
			foreach ($union_list as $u) {
				if($u['danhsachdoan']){
					foreach ($u['danhsachdoan'] as $ds) {
						if($ds['id_donvi'][0] == $id_donvi) $c1++;
					}
				}
				if($u['danhsachdoan_2']){
					foreach ($u['danhsachdoan_2'] as $ds2) {
						if($ds2['id_donvi'][0] == $id_donvi) $c1++;
					}
				}
			}
		}
		return $c1;
	}

	public function count_soluot_to_nguoi($query, $id_donvi, $id_canbo){
		$union_list = $this->_collection->find($query);
		$c1 = 0;
		if($union_list){
			foreach ($union_list as $u) {
				if($u['danhsachdoan']){
					foreach ($u['danhsachdoan'] as $ds) {
						if($ds['id_donvi'][0] == $id_donvi && $ds['id_canbo'] == $id_canbo) $c1++;
					}
				}
				if($u['danhsachdoan_2']){
					foreach ($u['danhsachdoan_2'] as $ds2) {
						if($ds2['id_donvi'][0] == $id_donvi && $ds2['id_canbo'] == $id_canbo) $c1++;
					}
				}
			}
		}
		return $c1;
	}

	public function count_songuoi($query, $id_donvi){
		$arr_canbo = array();
		$result = $this->_collection->find($query);
		foreach ($result as $s) {
			if(isset($s['danhsachdoan']) && $s['danhsachdoan']){
				foreach ($s['danhsachdoan'] as $ds) {
					if(!in_array($ds['id_canbo'], $arr_canbo) && $ds['id_donvi'][0] == $id_donvi){
						array_push($arr_canbo, $ds['id_canbo']);
					}
				}
			}
			if(isset($s['danhsachdoan_2']) && $s['danhsachdoan_2']){
				foreach ($s['danhsachdoan_2'] as $ds2) {
					if(!in_array($ds2['id_canbo'], $arr_canbo) && $ds2['id_donvi'][0] == $id_donvi){
						array_push($arr_canbo, $ds2['id_canbo']);
					}
				}
			}
		}
		return count($arr_canbo);
	}

	public function get_list_songuoi($query, $id_donvi){
		$arr_canbo = array();
		$result = $this->_collection->find($query);
		foreach ($result as $s) {
			if(isset($s['danhsachdoan']) && $s['danhsachdoan']){
				foreach ($s['danhsachdoan'] as $ds) {
					if(!in_array($ds['id_canbo'], $arr_canbo) && $ds['id_donvi'][0] == $id_donvi){
						array_push($arr_canbo, $ds['id_canbo']);
					}
				}
			}
			if(isset($s['danhsachdoan_2']) && $s['danhsachdoan_2']){
				foreach ($s['danhsachdoan_2'] as $ds2) {
					if(!in_array($ds2['id_canbo'], $arr_canbo) && $ds2['id_donvi'][0] == $id_donvi){
						array_push($arr_canbo, $ds2['id_canbo']);
					}
				}
			}
		}
		return $arr_canbo;
	}

	public function check_thongkephanloai($query){
		$fields = array('_id' => true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true;
		else return false;
	}

	public function count_thongkephanloai_quocgia($query){
		return $this->_collection->find($query)->count();
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
}
?>