<?php
class Users{
	const COLLECTION = 'users';
	public $id = '';
	public $username = '';
	public $password = '';
	public $roles = 0;
	public $person = '';
	public $logs = '';

	private $_mongo;
	private $_collection;
	private $_user;

	public function __construct(){
		$this->_mongo = DBConnect::init();
		$this->_collection = $this->_mongo->getCollection(Users::COLLECTION);
		if ($this->isLoggedIn()) $this->_loadData();
	}

	public function get_list(){
		return $this->_collection->find();
	}

	public function get_list_50(){
		return $this->_collection->find()->limit(50);	
	}

	public function get_one(){
		return $this->_collection->findOne(array('_id'=>new MongoId($this->id)));
	}

	public function get_list_condition($condition){
		return $this->_collection->find($condition);
	}

	public function check_exist_username(){
		$query = array('username'=> $this->username);
		$result = $this->_collection->findOne($query);
		if($result['_id']) return true;
		return false;
	}

	public function insert($query){
		$query = array('username'=> $this->username, 'password'=>md5($this->password), 'roles'=>$this->roles, 'person'=>$this->person);
		return $this->_collection->insert($query);
	}
	public function push_logs_in(){
		$query = array('$push' => array('logs' => array('in' => new MongoDate())));
		$condition = array('_id' => new MongoId($this->get_userid()));
		return $this->_collection->update($condition, $query);
	}
	public function push_logs_out(){
		$query = array('$push' => array('logs' => array('out' => new MongoDate())));
		$condition = array('_id' => new MongoId($this->get_userid()));
		return $this->_collection->update($condition, $query);
	}
	public function edit(){
		$condition = array('_id'=> new MongoId($this->id));
		if($this->password){
			$query = array('$set' => array(
					'username'=> $this->username,
					'password'=>md5($this->password),
					'roles'=>$this->roles,
					'person'=>$this->person));
		} else {
			$query = array('$set' => array(
					'username'=> $this->username,
					'roles'=>$this->roles,
					'person'=>$this->person));
		}
		return $this->_collection->update($condition, $query);
	}

	public function reset_password(){
		$condition = array('_id' => new MongoId($this->id));
		$query = array('$set' => array('password' => md5($this->password)));
		return $this->_collection->update($condition, $query);
	}

	public function delete(){
		return $this->_collection->remove(array('_id'=> new MongoId($this->id)));
	}

	//public function insert_list(){

	//}
	public function isLoggedIn() {
		return isset($_SESSION['user_id']);
	}

	public function getRole(){
		return $_SESSION['roles'];
	}

	public function get_username(){
		$result = $this->_collection->findOne(array("_id"=>new MongoId($_SESSION['user_id'])), array('username'=>true));
		return $result['username'];
	}

	public function get_userid(){
		return $_SESSION['user_id'];
	}

	public function is_admin(){
		return ($_SESSION['roles'] & ADMIN);
	}

	public function is_manager(){
		return ($_SESSION['roles'] & MANAGER);
	}

	public function is_updater(){
		return ($_SESSION['roles'] & UPDATER);	
	}

	public function is_student(){
		return ($_SESSION['roles'] & STUDENT);	
	}

	public function authenticate($username, $password){
		$query = array(
			'username' => $username,
			'password' => md5($password)
		);

		$this->_user = $this->_collection->findOne($query);
		if (empty($this->_user)) return false;
			$_SESSION['user_id'] = (string) $this->_user['_id'];
			$_SESSION['roles'] = (int) $this->_user['roles'];
			return true;
	}

	public function logout(){
		$_SESSION['user_id'] = false;
		unset($_SESSION['user_id']);
		//session_destroy();
	}

	/*public function __get($attr){
		if (empty($this->_user))
			return Null;
		switch($attr) {
			case 'address':
				$address = $this->_user['address'];
				return sprintf('Town: %s, Planet: %s', $address['town'], $address['planet']);
			case 'town':
				return $this->_user['address']['town']; 
			case 'planet':
				return $this->_user['address']['planet'];
			case 'password':
				return NULL;
			default:
				return (isset($this->_user[$attr])) ? $this->_user[$attr] : NULL;
		}
	}*/

	private function _loadData() {
		$id = new MongoId($_SESSION['user_id']);
		$this->_user = $this->_collection->findOne(array('_id' => $id));
	}
}

?>