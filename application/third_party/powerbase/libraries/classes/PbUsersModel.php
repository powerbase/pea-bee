<?php

class PbUsersModel extends PbTable {
	const TABLE_NAME = "pb_users";
	
	
	public function __construct() {
		parent::__construct(PbUsersModel::TABLE_NAME);
	}
	
	public function save(array $data, $id=null) {
		if (isset($data["password"])) {
			if ($data["password"] == "") unset($data["password"]);
			else $data["password"] = PbPassword::hash($data["password"]);
		} 
		if (($error = $this->check($data)) !== true) return $error;   
		return parent::save($data, $id);
	}
	
	public function createTable() {
		$forge = new PbDbForge($this);
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'user_id' => array(
				'type' => 'VARCHAR',
				'constraint' => '60',				
			),
			'password' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),
			'user_name' => array(
				'type' => 'TEXT',
			),
			'admin' => array(
				'type' => 'VARCHAR',
				'constraint' => '1',
				'default' => '0',
			),
			'group_id' => array(
				'type' => 'INT',
				'default' => '0',
			),
		);
		$forge->create("id", $fields);
		$this->insertAdministrator();
	}

	public function insertAdministrator() {
		if ($this->count() != 0) return;
		$settings = PbSettings::getInstance();
		$data = array();
		$data["user_id"] = $settings->get(PbSettings::ADMIN_USERID);
		$data["password"] = $settings->get(PbSettings::ADMIN_PASSWORD);
		$data["user_name"] = "Administrator";
		$data["admin"] = "1";
		$data["group_id"] = -1;
		$this->save($data);
	}
}
