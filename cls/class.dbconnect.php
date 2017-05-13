<?php
class DBConnect{
	const HOST = 'localhost';
	const PORT = 27017;
	const DBNAME = 'lanhsu';
	//const USERNAME = 'root';
	//const PASSWORD = 'root';
	private static $instance;
	public $connection; 
	public $database;
	private function __construct(){
		//$connectionString = sprintf('mongodb://%s:%s@%s:%d',DBConnect::USERNAME, DBConnect::PASSWORD, DBConnect::HOST, DBConnect::PORT);
		$connectionString = sprintf('mongodb://%s:%d', DBConnect::HOST, DBConnect::PORT);
		try {
			$this->connection = new MongoClient($connectionString);
			$this->database = $this->connection->selectDB(DBConnect::DBNAME);
		} catch (MongoConnectionException $e) { throw $e; }
	}
	static public function init() {
		if (!isset(self::$instance)) {
			$class = __CLASS__;
			self::$instance = new $class;
		}
		return self::$instance;
	}
	
	public function getCollection($name) {
		return $this->database->selectCollection($name);
	}
}

?>