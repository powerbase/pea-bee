<?php

class PbPassword {
	
	public static function weakPassword($passwd) {
		if (strlen($passwd) < 6) {
			return true;
		}
		return false;
	}
	
	public static function hash($passwd) {
		if (version_compare(phpversion(), "5.5.0") < 0) {
			require_once APPPATH . "third_party/powerbase/libraries/password.php";
		}
		return password_hash($passwd, PASSWORD_DEFAULT);
	} 
	
	public static function verify($passwd, $hash) {
		if (version_compare(phpversion(), "5.5.0") < 0) {
			require_once APPPATH . "third_party/powerbase/libraries/password.php";
		}
		return password_verify($passwd, $hash);
		
	}
}
