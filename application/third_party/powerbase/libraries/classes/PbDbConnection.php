<?php

class PbDbConnection {
	
	private $settings;
	
	private $connections;
	
	public static function connectionParam($dsn, $user, $passwd) {
		//FIXME: Value of the following parameters.
		$char_set="";
		$dbcollat="utf8_general_ci";
		
		$param = array(
			'dsn' => $dsn,           
			'hostname' => '',
			'username' => $user,
			'password' => $passwd,
			'database' => '',
			'dbdriver' => 'pdo',
			'dbprefix' => '',
			'pconnect' => false,
			'db_debug' => (ENVIRONMENT !== 'production'),
			'cache_on' => false,
			'cachedir' => '',
			'char_set' => $char_set,
			'dbcollat' => $dbcollat,
			'swap_pre' => '',
			'encrypt' => false,
			'compress' => false,
			'stricton' => false,
			'failover' => array(),
			'save_queries' => true
		);
		return $param;
	}
	
	public function __construct() {
		$this->settings = PbSettings::getInstance();
		$this->connections = $this->settings->get(PbSettings::DB_CONNECTIONS);
		if (!is_array($this->connections)) $this->connections = array();  
	}
	
	public function write() {
		$this->settings->set(PbSettings::DB_CONNECTIONS, $this->connections);
	}
	
	public function addConnection($name, $dsn, $user, $passwd) {
		$con[$name] = array("dsn"=>$dsn, "user"=>$user, "passwd"=>$passwd);
		$this->connections = array_merge($this->connections, $con);
	}
	
	public function getConnection($name) {
		return $this->connections[$name];
	}

	public static function checkDbConnection($dsn, $userid, $passwd) {
		try {
			new PDO($dsn, $userid, $passwd);
		} catch (Exception $e) {
			return false;
		}
		return true;
	}
	
	
}
