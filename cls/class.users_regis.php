<?php
class Users_Regis{
	const COLLECTION = 'users_regis';
	private $_mongo;
	private $_collection;
	
	public $id = '';
	public $email = '';
	public $password = '';
	public $status = 0; //0 khóa, 1 - hoạt động
	public $canbo = array(); //id_canbo, $id_donvi, $id_chucvu, $id_ham
	public $hoten = '';
	public $donvi = '';
	public $chucvu = '';
	public $dienthoai = '';
	public $date_post = '';
	public $id_user = '';
	public $id_user_regis = '';
	private $_userregis;
	public function __construct(){
		$this->_mongo = DBConnect::init();
		$this->_collection = $this->_mongo->getCollection(Users_Regis::COLLECTION);
	}
	public function get_all_list(){
		return $this->_collection->find();
	}
	public function get_list_condition($condition){
		return $this->_collection->find($condition);
	}
	public function get_one(){
		return $this->_collection->findOne(array('_id'=>new MongoId($this->id)));
	}
	public function insert(){
		$query = array('email' => $this->email,
						'password' => md5($this->password),
						'status' => intval($this->status),
						'canbo' => $this->canbo,
						'date_post' => new MongoDate(),
						'id_user' => new MongoId($this->id_user));
		return $this->_collection->insert($query);
	}

	public function register(){
		$query = array('email' => $this->email,
						'password' => md5($this->password),
						'status' => intval(0),
						'hoten' => $this->hoten,
						'donvi' => $this->donvi,
						'chucvu' => $this->chucvu,
						'dienthoai' => $this->dienthoai,
						'date_post_regis' => new MongoDate()
					);
		return $this->_collection->insert($query);	
	}

	public function edit(){
		$query = array('$set' => array(
						'password' => md5($this->password),
						'status' => intval($this->status),
						'canbo' => $this->canbo,
						'id_user' => $this->id_user ? new MongoId($this->id_user) : ''));

		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);
	}

	public function actived_account(){
		$query = array('$set' => array(
						'status' => intval($this->status),
						'canbo' => $this->canbo,
						'id_user' => $this->id_user ? new MongoId($this->id_user) : ''));

		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);	
	}

	public function delete(){
		$query = array('_id' => new MongoId($this->id));
		return $this->_collection->remove($query);
	}

	public function set_status(){
		$query = array('$set' => array('status' => $this->status));
		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);
	}

	public function change_password(){
		$query = array('$set' => array('password' => md5($this->password)));
		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update();
	}

	public function check_exists(){
		$query = array('email'=>$this->email);
		$fields = array('_id'=> true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true;
		else return false;
	}

	public function authenticate($email, $password){
		$query = array(
			'email' => $email,
			'password' => md5($password),
			'status' => 1);

		$this->_userregis = $this->_collection->findOne($query);
		if (empty($this->_userregis)) return false;
		$_SESSION['userregis_id'] = (string) $this->_userregis['_id'];
		return true;
	}

	public function logout(){
		$_SESSION['userregis_id'] = false;
		unset($_SESSION['userregis_id']);
		//session_destroy();
	}

	public function isLoggedIn() {
		return isset($_SESSION['userregis_id']);
	}

	public function get_userid(){
		return $_SESSION['userregis_id'];
	}

	public function generate_capcha(){
		$random_alpha = md5(rand());
		$captcha_code = substr($random_alpha, 0, 3);
		$_SESSION["captcha_code"] = $captcha_code;
		return '<img src="captcha_code.php?captcha_code='.$captcha_code.'" />';
	}

	public function get_capcha(){
		return $_SESSION['captcha_code'];
	}
}

?>