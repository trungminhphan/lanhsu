<?php
class DonVi{
	const COLLECTION = 'donvi';
	private $_mongo;
	private $_collection;
	public $id = '';
	public $ten = '';
	public $tentienganh = '';
	public $diachi = '';
	public $dienthoai = '';
	public $email = '';
	public $id_phanloaidonvi = '';
	public $id_linhvuc = '';
	public $id_quocgia = '';
	public $ghichu = '';
	public $public = 0; //0-khong thay, 1-cho thay o dang ky truc tuyen
	public $id_user = '';

	public function __construct(){
		$this->_mongo = DBConnect::init();
		$this->_collection = $this->_mongo->getCollection(DonVi::COLLECTION);
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
	public function get_all_list_regis(){
		return $this->_collection->find(array('public' => 1))->sort(array('ten'=> 1));	
	}
	public function get_list_condition($condition){
		return $this->_collection->find($condition)->sort(array('ten'=> 1));
	}
	public function get_list_to_position_condition($condition, $position, $limit){
		return $this->_collection->find($condition)->skip($position)->limit($limit);//->sort(array('code'=>-1));
	}
	public function get_all_list_to_ten(){
		return $this->_collection->find(array('ten'=> new MongoRegex('/' . $this->ten .'/i')))->sort(array('_id'=> -1));
	}
	public function insert(){
		$query = array(
			'ten' => trim($this->ten),
			'tentienganh' => $this->tentienganh,
			'diachi' => $this->diachi,
			'dienthoai' => $this->dienthoai,
			'email' => $this->email,
			'id_phanloaidonvi' => $this->id_phanloaidonvi ? new MongoId($this->id_phanloaidonvi) : '',
			'id_linhvuc' => $this->id_linhvuc ? new MongoId($this->id_linhvuc) : '',
			'id_quocgia' => $this->id_quocgia ? new MongoId($this->id_quocgia) : '',
			'ghichu' => $this->ghichu,
			'public' => $this->public,
			'id_user' => new MongoId($this->id_user));
		return $this->_collection->insert($query);
	}
	public function edit(){
		$query = array('$set' => array(
			'ten' => trim($this->ten),
			'tentienganh' => $this->tentienganh,
			'diachi' => $this->diachi,
			'dienthoai' => $this->dienthoai,
			'email' => $this->email,
			'id_phanloaidonvi' => $this->id_phanloaidonvi ? new MongoId($this->id_phanloaidonvi) : '',
			'id_linhvuc' => $this->id_linhvuc ? new MongoId($this->id_linhvuc) : '',
			'id_quocgia' => $this->id_quocgia ? new MongoId($this->id_quocgia) : '',
			'ghichu' => $this->ghichu, 'public' => $this->public,'id_user' => new MongoId($this->id_user)));
		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);
	}
	public function delete(){
		return $this->_collection->remove(array('_id'=> new MongoId($this->id)));
	}
	public function check_exists(){
		$query = array('ten'=>trim($this->ten));
		$fields = array('_id'=> true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true;
		else return false;
	}
	public function set_id_user(){
		$query = array('$set' => array('id_user' => new MongoId($this->id_user)));
		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);
	}
	
	public function insert_level1(){
		$query = array('ten' => $this->ten);
		return $this->_collection->insert($query);
	}

	public function insert_level2(){
		$query = array('$push' => array('level2' => array('_id' => new MongoId(), 'ten'=> $this->ten)));
		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);
	}
	public function insert_level3(){
		$query = array('$push' => array('level2.'.$this->k2.'.level3' => array('_id' => new MongoId(), 'ten'=> $this->ten)));
		$condition = array('level2._id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);
	}

	public function insert_level4(){
		$query = array('$push' => array('level2.'.$this->k2.'.level3.'.$this->k3.'.level4' => array('_id' => new MongoId(), 'ten'=> $this->ten)));
		$condition = array('level2.level3._id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);
	}

	public function edit_level1(){
		$query = array('$set' => array('ten' => $this->ten));
		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);
	}
	public function edit_level2(){
		$query = array('$set' => array('level2.'.$this->k2.'.ten' => $this->ten));
		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);
	}

	public function edit_level3(){
		$query = array('$set' => array('level2.'.$this->k2.'.level3.'.$this->k3.'.ten' => $this->ten));
		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);
	}

	public function edit_level4(){
		$query = array('$set' => array('level2.'.$this->k2.'.level3.'.$this->k3.'.level4.'.$this->k4.'.ten' => $this->ten));
		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);
	}

	public function delete_level1(){
		$query = array('_id' => new MongoId($this->id));
		return $this->_collection->remove($query);
	}
	public function delete_level2(){
		$query = array('$pull' => array('level2' => array('_id' => new MongoId($this->id_delete))));
		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);
	}
	public function delete_level3(){
		$query = array('$pull' => array('level2.'.$this->k2. '.level3' => array('_id' => new MongoId($this->id_delete))));
		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);
	}	
	public function delete_level4(){
		$query = array('$pull' => array('level2.'.$this->k2. '.level3.'.$this->k3.'.level4' => array('_id' => new MongoId($this->id_delete))));
		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);
	}
	/*public function tendonvi($arr){
		if($arr[0]) $condition = array('_id' => new MongoId($arr[0]));
		else $condition = array();
		$result = $this->_collection->findOne($condition);
		$cap1='';$cap2='';$cap3='';$cap4='';
		$str_donvi = '';
		if($arr[0] && isset($result['ten'])) $str_donvi = $result['ten'];//$cap1 = $result['ten'];
		if(isset($result['level2']) && $arr[1]){
			foreach ($result['level2'] as $a2) {
				if($arr[1]==$a2['_id']) $str_donvi = $a2['ten'] . ' - ' . $str_donvi;//$cap2 = $a2['ten'];
				if(isset($a2['level3']) && $arr[2]){
					foreach ($a2['level3'] as $a3) {
						if($a3['_id']==$arr[2]) $str_donvi = $a3['ten'] . ' - ' . $str_donvi; //$cap3 = $a3['ten'];
						if(isset($a3['level4']) && $arr[3]){
							foreach ($a3['level4'] as $a4) {
								if($a4['_id']==$arr[3]) $str_donvi = $a4['ten'] . ' - ' . $str_donvi; //$cap4 = $a4['ten'];
							}
						}
					}
				}
			}	
		}
		return $str_donvi;
	}*/

	public function tendonvi($arr){
		$str_donvi = '';
		if(isset($arr[0]) && strlen($arr[0])==24){
			$condition = array('_id' => new MongoId($arr[0]));
			$result = $this->_collection->findOne($condition);
			
			$cap1='';$cap2='';$cap3='';$cap4='';
			if($arr[0] && isset($result['ten'])) $str_donvi = $result['ten'];//$cap1 = $result['ten'];
			if(isset($result['level2']) && $result['level2'] && $arr[1]){
				foreach ($result['level2'] as $a2) {
					if(isset($a2['_id']) && $arr[1]==$a2['_id']) $str_donvi = $str_donvi . ' - ' . $a2['ten'];//$cap2 = $a2['ten'];
					if(isset($a2['level3']) && $a2['level3'] && $arr[2]){
						foreach ($a2['level3'] as $a3) {
							if(isset($a3['_id']) && $a3['_id']==$arr[2]) $str_donvi =  $str_donvi . ' - ' . $a3['ten']; //$cap3 = $a3['ten'];
							if(isset($a3['level4']) && $a3['level4'] && $arr[3]){
								foreach ($a3['level4'] as $a4) {
									if(isset($a4['_id']) && $a4['_id']==$arr[3]) $str_donvi = $str_donvi . ' - ' . $a4['ten']; //$cap4 = $a4['ten'];
								}
							}
						}
					}
				}	
			}
		} 
		return $str_donvi;
	}

	public function count_all(){
		//return $this->_collection->count();
		$count = 0;
		$donvi_list = $this->get_all_list();
		if($donvi_list){
			foreach ($donvi_list as $a1) {
				$count++;
				if(isset($a1['level2']) && $a1['level2']){
					foreach ($a1['level2'] as $a2) {
						$count++;
						if(isset($a2['level3']) && $a2['level3']){
							foreach ($a2['level3'] as $a3) {
								$count++;
								if(isset($a3['level4']) && $a3['level4']){
									foreach ($a3['level4'] as $a4) {
										$count++;
									}
								}
							}
						}
					}
				}
			}
		}
		return $count;
	}

	public function check_dm_phanloaidonvi($id_phanloaitochuc){
		$query = array('_id' => true);
		$condition = array('id_phanloaitochuc' => new MongoId($id_phanloaitochuc));
		$result = $this->_collection->findOne($condition, $query);
		if($result['_id']) return true; else return false;
	}

	public function check_dm_linhvuc($id_linhvuc){
		$query = array('_id' => true);
		$condition = array('id_linhvuc' => new MongoId($id_linhvuc));
		$result = $this->_collection->findOne($condition, $query);
		if($result['_id']) return true; else return false;
	}

	public function count_to_user(){
		$query = array('id_user' => new MongoId($this->id_user));
		return $this->_collection->count($query);
	}
}
?>