<?php

class PbSettingsDb {
	private $config;
	private $settingsDb;
	private $db;
	private $initialState;

	protected function __construct() {
		$this->config =& load_class('Config', 'core');
		$this->settingsDb = $this->config->item("pb_settings_db_location");
		if (empty($this->settingsDb)) {
			$this->settingsDb = BASEPATH . "../settings.db";
		}
		$this->initialState = !$this->existsDb();
		$this->db = new PDO("sqlite:" . $this->settingsDb);
		if (!$this->existsDb()) throw new PbException("unable create the settings database.");
		if ($this->initialState()) $this->query("CREATE TABLE settings (key text primary key, value text)");
	}
	
	public function initialize(array $settings) {
		$errors = array();
		if (is_empty($settings["system_name"])) {
			$errors[] = PbTextPerLang::get("is_required", PbTextPerLang::get("system_name"));
		}
		if (is_empty($settings["admin_userid"])) {
			$errors[] = PbTextPerLang::get("is_required", PbTextPerLang::get("admin_userid"));
		}
		if (is_empty($settings["admin_password"])) {
			$errors[] = PbTextPerLang::get("is_required", PbTextPerLang::get("admin_password"));
		} else {
			if (PbPassword::weakPassword($settings["admin_password"])) {
				$errors[] = PbTextPerLang::get("weak_password", PbTextPerLang::get("admin_password"));
			}
		}
		if (is_empty($settings["pdo_dsn"])) {
			$errors[] = PbTextPerLang::get("is_required", PbTextPerLang::get("pdo_dsn"));
		} else {
			if (!PbDbConnection::checkDbConnection($settings["pdo_dsn"], $settings["db_username"], $settings["db_password"])) {
				$errors[] = PbTextPerLang::get("db_connection_failed");
			}
		}
		if (count($errors) != 0) return $errors;
		
		self::set(PbSettings::SYSTEM_NAME, $settings["system_name"]);
		self::set(PbSettings::ADMIN_USERID, $settings["admin_userid"]);
		self::set(PbSettings::ADMIN_PASSWORD, PbPassword::hash($settings["admin_password"]));
		$con = new PbDbConnection();
		$con->addConnection("default", $settings["pdo_dsn"], $settings["db_username"], $settings["db_password"]);
		$con->write();
		
		return true;
	}

	public function initialState() {
		return $this->initialState;
	}

	public function unfold() {
		$settings = array();
		$data = $this->read("SELECT * FROM settings");
		foreach($data as $setting) $settings[$setting["key"]] = $setting["value"];
		return $settings;
	}
	
	public function existsDb() {
		return is_file($this->settingsDb);
	}

	public function isEmptyDb() {
		if (!$this->existsDb()) return true;
		$settings = $this->read("SELECT count(*) FROM settings");
		if (!is_array($settings)) return true;
		return array_shift($settings[0]) == 0 ? true : false;
	}

	public function serialize($data) {
		return json_encode($data);
	}

	public function unserialize($data) {
		return json_decode($data, true);
	}

	public function get($key) {
		$sql = "select value from settings where key = " . $this->db->quote($key);
		$settings = $this->read($sql);
		if (!isset($settings[0]["value"])) return null; 
		return $this->unserialize($settings[0]["value"]);
	}

	public function set($key, $value) {
		if ($value === null) $value = ""; 
		$exists = true;
		if (self::get($key) === null) $exists = false;
		$sql = "INSERT INTO settings VALUES(" . $this->db->quote($key) . ", " . $this->db->quote($this->serialize($value)) . ")"; 
		if ($exists) {
			$sql = "UPDATE settings SET value = " . $this->db->quote($this->serialize($value)) . " WHERE key = " . $this->db->quote($key);
		}
		return $this->query($sql);
	}

	public function read($sql) {
		try {
			$stmt = $this->db->query($sql);
			$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch(Exception $e) {
			throw new PbException($e->getMessage());
		}
		return $res;
	}

	public function query($sql) {
		$res = null;
		try {
			$res = $this->db->exec($sql);
		} catch(Exception $e) {
			throw new PbException($e->getMessage());
		}
		return $res;
	}

}
