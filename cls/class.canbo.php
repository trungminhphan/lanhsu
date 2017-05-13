<?php
class CanBo {
	const COLLECTION = 'canbo';
	private $_mongo;
	private $_collection;
	public $id = ''; public $code = 0;public $hinhanh = ''; public $cmnd = '';
	public $passport = ''; //array('sohochieu', 'ngayhethan')
	public $dangvien = 0;public $tinhuyvien=0;public $loaicanbo='';//Công chức, Viên Chức
	public $hoten = ''; public $ngaysinh = ''; public $gioitinh = ''; public $id_quoctich = '';
	public $diachi = ''; public $dienthoai = ''; public $email = '';public $id_nghenghiep=''; public $id_dantoc='';
	public $donvi = array(); //{ id_donvi = array(4 cap), $id_chucvu, $id_ham ngaynhap }
	public $ghichu = ''; public $date_post = ''; public $id_user = '';
	
	public function __construct(){
		$this->_mongo = DBConnect::init();
		$this->_collection = $this->_mongo->getCollection(CanBo::COLLECTION);
	}
	public function get_one(){
		return $this->_collection->findOne(array('_id'=> new MongoId($this->id)));
	}
	public function get_one_to_ten(){
		return $this->_collection->findOne(array('ten'=> $this->ten));
	}
	public function get_all_list(){
		return $this->_collection->find()->sort(array('ten'=> 1));
	}
	public function get_list_condition($condition){
		return $this->_collection->find($condition)->sort(array('ten'=> 1));
	}
	public function get_list_condition_huu($condition){
		return $this->_collection->find($condition)->sort(array('ngaysinh'=> -1));	
	}
	public function count_all(){
		return $this->_collection->find()->count();
	}
	public function get_list_to_position_condition($condition, $position, $limit){
		return $this->_collection->find($condition)->skip($position)->limit($limit);//->sort(array('code'=>-1));
	}
	public function get_totalFilter($condition){
		return $this->_collection->find($condition)->sort(array('code'=>-1))->count();
	}
	public function get_all_list_to_ten(){
		return $this->_collection->find(array('ten'=> new MongoRegex('/' . $this->ten .'/i')))->sort(array('_id'=> -1));
	}
	public function insert(){
		$query = array(
			'_id' => new MongoId($this->id),
			'dangvien' => $this->dangvien,
			'loaicongchuc' => $this->loaicongchuc,
			'tinhuyvien' => $this->tinhuyvien,
			'code' => $this->code,
			'hinhanh' => $this->hinhanh,
			'cmnd' => $this->cmnd,
			'passport' => $this->passport,
			'hoten' => $this->hoten, 'ngaysinh' => $this->ngaysinh,
			'gioitinh' => $this->gioitinh, 'id_quoctich' => $this->id_quoctich, 'diachi' => $this->diachi,
			'dienthoai' =>$this->dienthoai, 'email' => $this->email,
			'id_nghenghiep' => $this->id_nghenghiep ? new MongoId($this->id_nghenghiep) : '',
			'id_dantoc' => $this->id_dantoc ? new MongoId($this->id_dantoc) : '',
			'donvi' => $this->donvi, 'ghichu' => $this->ghichu, 'date_post' => new MongoDate(), 'id_user' => new MongoId($this->id_user)
		);

		//logs 
		$logs = new Logs();
		$logs->id = new MongoId();
		$logs->action = 'ADD';
		$logs->collections = 'canbo';
		$logs->datas = $query;
		$logs->id_user = $this->id_user;
		$logs->insert();
		return $this->_collection->insert($query);
	}

	public function push_chucvu(){
		$query = array('$push' => array('donvi' => array('$each' => array($this->donvi), '$position' => 0)));
		//$query = array('$push' => array('donvi' => $this->donvi));
		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);
	}

	public function set_chucvu($id_donvi){
		$query = array('$set' => $this->donvi);
		$condition = array('_id' => new MongoId($this->id), 'donvi.id' => new MongoId($id_donvi));
		return $this->_collection->update($condition, $query);
	}

	public function pull_chucvu(){
		$query = array('$pull' => array('donvi' => $this->donvi));
		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);	
	}
	public function edit(){
		$query = array('$set' => array(
			'_id' => new MongoId($this->id),
			'dangvien' => $this->dangvien,
			'loaicongchuc' => $this->loaicongchuc,
			'tinhuyvien' => $this->tinhuyvien,
			'hinhanh' => $this->hinhanh,
			'cmnd' => $this->cmnd, 'passport' => $this->passport,
			'hoten' => $this->hoten,
			'ngaysinh' => $this->ngaysinh,
			'gioitinh' => $this->gioitinh, 'id_quoctich'=> new MongoId($this->id_quoctich), 'diachi' => $this->diachi,
			'dienthoai' =>$this->dienthoai, 'email' => $this->email, 
			'id_nghenghiep' => $this->id_nghenghiep ? new MongoId($this->id_nghenghiep) : '',
			'id_dantoc' => $this->id_dantoc ? new MongoId($this->id_dantoc) : '',
			'ghichu' => $this->ghichu,
			'id_user' => new MongoId($this->id_user)
		));

		$condition = array('_id' => new MongoId($this->id));
		//logs 
		$logs = new Logs();
		$logs->id = new MongoId();
		$logs->action = 'EDIT';
		$logs->collections = 'canbo';
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
		$logs->collections = 'canbo';
		$logs->datas = $result;
		$logs->id_user = $this->id_user;
		$logs->insert();
		return $this->_collection->remove(array('_id'=> new MongoId($this->id)));
	}

	//kiem tra donvi va hoten
	public function check_exists($id_donvi){
		//$query = array('ten'=>$this->ten);
		//$query = array('$or' => array(array('cmnd' => $this->cmnd), array('passport' => $this->passport)));
		//$query = array('code' => $this->code);
		//passport $cmnd $hoten $donvi
		$query = array(
			'cmnd' => $this->cmnd,
			'passport' => $this->passport,
			'hoten' => $this->hoten,
			'donvi.id_donvi.0' => $id_donvi[0],
			'donvi.id_donvi.1' => $id_donvi[1],
			'donvi.id_donvi.2' => $id_donvi[2],
			'donvi.id_donvi.3' => $id_donvi[3]
		);
		$fields = array('_id'=> true); 
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true;
		else return false;
	}

	public function check_dm_quocgia(){
		$query = array('_id' => true);
		$condition = array('id_quoctich' => new MongoId($this->id_quoctich));
		$result = $this->_collection->findOne($condition, $query);
		if($result['_id']) return true; else return false;
	}

	public function check_dm_donvi($id_donvi){
		$query = array('_id' => true);
		$condition = array('donvi.id_donvi' => new MongoId($id_donvi));
		$result = $this->_collection->findOne($condition, $query);
		if($result['_id']) return true; else return false;	
	}
	public function check_dm_chucvu($id_chucvu){
		$query = array('_id' => true);
		$condition = array('donvi.id_chucvu' => new MongoId($id_chucvu));
		$result = $this->_collection->findOne($condition, $query);
		if($result['_id']) return true; else return false;	
	}
	public function get_maxcode(){
		$query = array('$group' => array( '_id' => '', 'maxCode' => array('$max' => '$code')));
		$result = $this->_collection->aggregate($query);
		if(isset($result['result'][0]['maxCode']) && $result['result'][0]['maxCode']) return $result['result'][0]['maxCode'] + 1;
		else return 1;
	}

	public function check_dm_ham($id_ham){
		$query = array('_id' => true);
		$condition = array('donvi.id_ham' => new MongoId($id_ham));
		$result = $this->_collection->findOne($condition, $query);
		if($result['_id']) return true; else return false;	
	}
	
	public function check_dm_nghenghiep($id_nghenghiep){
		$query = array('_id' => true);
		$condition = array('donvi.id_nghenghiep' => new MongoId($id_nghenghiep));
		$result = $this->_collection->findOne($condition, $query);
		if($result['_id']) return true; else return false;	
	}

	public function check_dm_dantoc($id_dantoc){
		$query = array('_id' => true);
		$condition = array('donvi.id_dantoc' => new MongoId($id_dantoc));
		$result = $this->_collection->findOne($condition, $query);
		if($result['_id']) return true; else return false;	
	}

	public function count_to_user(){
		$query = array('id_user' => new MongoId($this->id_user));
		return $this->_collection->count($query);
	}
}
?>