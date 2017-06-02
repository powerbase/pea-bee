<?php

final class PbSettings extends PbSettingsDb {
	
	const SYSTEM_NAME = "system_name";
	const ADMIN_USERID = "admin_userid";
	const ADMIN_PASSWORD = "admin_password";
	const DB_CONNECTIONS = "db_connections";
	
	private static $instance;

	private $settings;

	/**
	 * Gets instance.
	 * @return PbSettings
	 */
	public static function getInstance() {
		self::init();
		return self::$instance;
	}
	
	private static function init() {
		if (empty(self::$instance)) self::$instance = new self();
	}
	
	protected function __construct() {
		parent::__construct();
		if (!parent::existsDb()) throw new PbException("can not found the settings database.");
		$this->settings = $this->unfold();
	}
	
	public function get($key) {
		return $this->unserialize($this->settings[$key]);
	}

	public function set($key, $value) {
		$res = parent::set($key, $value);
		$this->settings[$key] = $this->serialize($value);
		return $res;
	}
	
	public function exists($key) {
		return parent::get($key) === null ? false : true;
	}
	
}
