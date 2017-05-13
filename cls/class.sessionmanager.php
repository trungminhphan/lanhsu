<?php
class SessionManager {
	//name of collection where sessions will be stored
	const COLLECTION = 'sessions';
	//Expire session after 10 mins in inactivity
	const SESSION_TIMEOUT = 6000;
	//Expire session after 1 hour
	const SESSION_LIFESPAN = 36000;
	//name of the session cookie
	const SESSION_NAME = 'mongosessid';
	const SESSION_COOKIE_PATH = 'sessions';
	//Should be the domain name of you web app, for example
	//mywebapp.com. DO NOT use empty string unless you are 
	//running on a local environment.
	const SESSION_COOKIE_DOMAIN = '';
	private $_mongo;
	private $_collection;
	//represents the current session
	private $_currentSession;

	public function __construct(){
		$this->_mongo = DBConnect::init();
		$this->_collection = $this->_mongo->getCollection(SessionManager::COLLECTION);
		session_set_save_handler(array(&$this, 'open'), array(&$this, 'close'), array(&$this, 'read'), array(&$this, 'write'), array(&$this, 'destroy'), array(&$this, 'gc'));
		//Set session garbage collection period
		ini_set('session.gc_maxlifetime', SessionManager::SESSION_LIFESPAN);
		//set session cookie configurations
		//start the session
		ob_start();
		session_start();
		ob_clean();
		session_set_cookie_params(SessionManager::SESSION_LIFESPAN, SessionManager::SESSION_COOKIE_PATH, SessionManager::SESSION_COOKIE_DOMAIN);
		//Replace 'PHPSESSID' with 'mongosessid' as the
		//session name
		session_name(SessionManager::SESSION_NAME);
		session_cache_limiter('nocache');
	}

	public function open($path, $name) {
		return true;
	}

	public function close() {
		return true;
	}

	public function read($sessionId) {
		$query = array('session_id'=>$sessionId,'timedout_at'=> array('$gte' => time()), 'expired_at' => array('$gte'=>time()));
		$result = $this->_collection->findOne($query);
		$this->_currentSession = $result;
		if(!isset($result['data'])) {
			return '';
		}
		return $result['data'];
	}

	public function write($sessionId, $data) {
		$expired_at = time() + self::SESSION_TIMEOUT;
		$new_obj = array('data' => $data, 'timedout_at' => time() + self::SESSION_TIMEOUT, 'expired_at' => (empty($this->_currentSession)) ? time()+ SessionManager::SESSION_LIFESPAN : $this->_currentSession['expired_at']);
		$query = array('session_id' => $sessionId);
		$this->_collection->update($query, array('$set' => $new_obj), array('upsert' => true));
		return true;
	}
	public function destroy($sessionId) {
		$this->_collection->remove(array('session_id' => $sessionId));
		return true;
	}
	public function gc(){
		$query = array( 'expired_at' => array('$lt' => time()));
		$this->_collection->remove($query);
		return true;
	}
	public function __destruct() {
		session_write_close();
	}
}
//initiate the session
//$session = new SessionManager();

?>