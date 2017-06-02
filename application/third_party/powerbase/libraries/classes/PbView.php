<?php

class PbView {
	
	private $_ctrl;
	
	private $_data;
	
	public function __construct(PB_Controller $ctrl) {
		$this->_ctrl = $ctrl;
		$this->_data = $this->_ctrl->getData();
	}

	public function __get($key) {
		return $this->value($key);
	}

	public function value($key, $null=false) {
		if ($this->exists($key)) return $this->exists($key, true);
		return $null ? null : "";
	}
	
	public function exists($key, $value=false) {
		$exists = true;

		$keys = array("var"=>$key, "elem"=>array());
		preg_match_all("/(.*?)\[(.*?)\]+/", $key, $m);
		if (count($m[1]) !== 0) $keys = array("var"=>$m[1][0], "elem"=>$m[2]);
		
		if (!isset($this->_data[$keys["var"]])) return false;
		if (empty($keys["elem"])) {
			return $value ? $this->_data[$keys["var"]] : $exists;
		}
		
		$var = $this->_data[$keys["var"]];
		for($i = 0; $i < count($keys["elem"]); $i++) {
			if (!isset($var[$keys["elem"][$i]])) return false;
			$var = $var[$keys["elem"][$i]];
		}
		return $value ? $var : $exists;
	}
	
	public function responce($value) {
		echo $value . PHP_EOL;
	}

}
