<?php
class QuocGia{
	const COLLECTION = 'quocgia';
	private $_mongo;
	private $_collection;
	public $id = '';
	public $ten = '';
	public $ghichu = '';
	public $id_user = '';
	
	public function __construct(){
		$this->_mongo = DBConnect::init();
		$this->_collection = $this->_mongo->getCollection(QuocGia::COLLECTION);
	}

	public function get_one(){
		return $this->_collection->findOne(array('_id'=> new MongoId($this->id)));
	}

	public function get_one_to_ten(){
		return $this->_collection->findOne(array('ten'=> $this->ten));
	}

	public function get_id($ten){
		if($ten && trim($ten) != ''){
			$query = array('_id' => true);
			$condition = array('ten' => new MongoRegex('/' . $ten . '/i'));
			$result = $this->_collection->findOne($condition, $query);
			if($result['_id']) return $result['_id']; else return false;
		} else {
			return false;
		}
	}

	public function get_all_list(){
		return $this->_collection->find()->sort(array('ten'=> 1));
	}
	public function get_list_condition($condition){
		return $this->_collection->find($condition)->sort(array('ten'=> 1));
	}
	public function get_all_list_to_ten(){
		return $this->_collection->find(array('ten'=> new MongoRegex('/' . $this->ten .'/i')))->sort(array('_id'=> -1));
	}
	public function insert(){
		return $this->_collection->insert(array('_id'=> new MongoId($this->id), 'ten'=> trim($this->ten), 'id_user' => new MongoId($this->id_user)));
	}

	public function set_id_user(){
		$query = array('$set' => array('id_user' => new MongoId($this->id_user)));
		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);
	}

	public function delete(){
		return $this->_collection->remove(array('_id'=> new MongoId($this->id)));
	}

	public function edit(){
		return $this->_collection->update(array('_id'=> new MongoId($this->id)), array('ten'=> $this->ten,'id_user' => new MongoId($this->id_user)));
	}

	public function check_exists(){
		$query = array('ten'=>$this->ten);
		$fields = array('_id'=> true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true;
		else return false;
	}

	public function get_quocgia_abtc($arr){
		$str_quocgia = '';
		if(count($arr)){
			foreach ($arr as $key => $value) {
				$this->id = $value; $qg = $this->get_one();
				$str_quocgia .= $qg['ten'] . '   ';
			}
		}
		return $str_quocgia;
	}

	public function get_quoctich($arr){
		$str_quoctich = '';$arr_quoctich = array();
		if($arr && count($arr) > 0){
			foreach ($arr as $key => $value) {
				if($value && strlen($value)==24){
					$this->id = $value;
					$result = $this->get_one();
					if($result['ten']) array_push($arr_quoctich, $result['ten']);
				}
			}
		}

		if(count($arr_quoctich) > 1){
			$str_quoctich = implode(" - ", $arr_quoctich);
		} else {
			if(count($arr_quoctich) > 0 && $arr_quoctich[0]){
				$str_quoctich = $arr_quoctich[0];
			} else {
				$str_quoctich = '';
			}

		}
		return strval($str_quoctich);
	}

	public function count_all(){
		return $this->_collection->count();
	}
	public function count_to_user(){
		$query = array('id_user' => new MongoId($this->id_user));
		return $this->_collection->count($query);
	}
}
?>