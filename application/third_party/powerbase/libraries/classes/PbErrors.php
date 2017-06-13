<?php

class PbErrors {
	private $errors;
	
	public function __construct() {
		$this->errors = array();
	}
	
	public function push($message, $table, $item, $level=PbError::PB_ERROR_LEVEL_ERROR) {
		$this->errors[] = new PbError($message, $table, $item, $level);
	}
	
	public function hasError() {
		return (is_empty($this->errors) ? false : true);
	}
	
	public function getErrors() {
		return $this->errors;
	}
	
	public function getMessages() {
		$messages = array();
		/** @var PbError $error */
		foreach($this->errors as $error) $messages[] = $error->getMessage();
		return $messages;
	}
}

class PbError {
	const PB_ERROR_LEVEL_WARNING = "warning";
	const PB_ERROR_LEVEL_ERROR = "error";
	const PB_ERROR_LEVEL_FATAL = "fatal";
	
	private $message;
	
	private $table;

	private $item;
	
	private $level;
	
	public function __construct($message, $table, $item, $level) {
		$this->message = $message;
		$this->table = $table;
		$this->item = $item;
		$this->level = $level;
	}

	/**
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * @return string
	 */
	public function getTable() {
		return $this->table;
	}

	/**
	 * @return string
	 */
	public function getItem() {
		return $this->item;
	}

	/**
	 * @return string
	 */
	public function getLevel() {
		return $this->level;
	}
	
}

