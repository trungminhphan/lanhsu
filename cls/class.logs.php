<?php
class Logs{
	const COLLECTION = 'logs';
	private $_mongo;
	private $_collection;

	public $id = '';
	public $action = ''; //ADD, EDIT, DELETE
	public $collections = ''; //doanra, doanvao, abtc
	public $datas = ''; //all data in collections...
	public $date_post = '';
	public $id_user = '';

	public function __construct(){
		$this->_mongo = DBConnect::init();
		$this->_collection = $this->_mongo->getCollection(Logs::COLLECTION);
	}
	public function get_one(){
		return $this->_collection->findOne(array('_id'=> new MongoId($this->id)));
	}
	public function get_all_list(){
		return $this->_collection->find()->sort(array('_id'=> 1))->limit(50);
	}
	public function get_list_condition($condition){
		return $this->_collection->find($condition)->sort(array('date_post'=> -1));
	}

	public function insert(){
		$query = array('_id' => new MongoId($this->id),
					'action' => $this->action,
					'collections' => $this->collections,
					'datas' => $this->datas,
					'date_post' => new MongoDate(),
					'id_user' => new MongoId($this->id_user));
		$this->_collection->insert($query);
	}
}