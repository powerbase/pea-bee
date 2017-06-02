<?php

class PbTextPerLang extends stdClass {
	
	private static $lang;
	private static $text;
	
	public function __construct($lang=null) {
		if ($lang !== null) self::init($lang);
	}
	
	public function __get($key) {
		return $this->text($key);
	}
	
	public function text($key) {
		$args = func_get_args();
		return call_user_func(array("PbTextPerLang", "get"), $args);
	}

	public static function get($args) {
		$params = array();
		if (is_array($args)) {
			$key = $args[0];
			for($i = 1; $i < count($args); $i++) $params[] = $args[$i];
		} else {
			$key = $args;
			for($i = 1; $i < func_num_args(); $i++) $params[] = func_get_arg($i);
		} 
		if (!isset(self::$text[strtolower($key)])) return "";
		$value = self::$text[strtolower($key)];
		$c = 0;
		foreach($params as $p) $value = str_replace("%".++$c, $p, $value);
		return $value;
	} 
	
	public static function init($lang=null) {
		if ($lang === null) {
			if (!empty(self::$lang)) return;
			/** @var CI_Config $CFG */
			$CFG =& load_class('Config', 'core');
			self::$lang = $CFG->config["pb_lang"];
		} else {
			self::$lang = $lang;
		}
		$text = array();
		require_once APPPATH . "third_party/powerbase/lang/" . self::$lang . ".php";
		self::$text = $text;
	} 
}
PbTextPerLang::init();
