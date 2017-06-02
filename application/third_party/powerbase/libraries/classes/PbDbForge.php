<?php

class PbDbForge {
	
	private $model;
	
	public function __construct(PbTable $model) {
		$this->model = $model;
	}
	
	public function create($pk, $fields) {
		$this->addField($fields);
		$this->addKey($pk);
		$this->createTable($this->model->getTableName());
	}

	/**
	 * Add field.
	 * @param array $field
	 */
	public function addField(array $field) {
		$this->model->dbforge->add_field($field);
	}

	/**
	 * Add key.
	 * default is primary key.
	 * @param $key
	 * @param bool $primary
	 */
	public function addKey($key, $primary = true) {
		$this->model->dbforge->add_key($key, $primary);
	}

	/**
	 * Create Table.
	 *
	 * @param $table
	 * @param bool $if_not_exists
	 * @param array $attributes
	 */
	public function createTable($table, $if_not_exists = FALSE, array $attributes = array()) {
		$this->model->dbforge->create_table($table, $if_not_exists, $attributes);
	}

}
