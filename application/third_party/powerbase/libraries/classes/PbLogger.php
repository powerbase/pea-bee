<?php
class PbLogger {
	public static $fileName;
	
	//emerg > alert > crit > error > warn > notice > info > debug
	private static $level = array(
		"emerg"   => 8,
		"alert"   => 7,
		"crit"    => 6,
		"error"   => 5,
		"warning" => 4,
		"notice"  => 3,
		"info"    => 2, 
		"debug"   => 1,
	);
	

    private static function logging($message, $header="", $name="") {
		if (self::$level[strtolower($header)] < self::getLevel()) {
			return;
		}
		$o = PHP_EOL;
        $o .= self::getTime();
        $o .= " [".$header."]";
        $o .= " - ";
		$o .= $_SERVER["REMOTE_ADDR"];
		$o .= " - ";
		$bt = debug_backtrace();
		if ($header == "ERROR") {
			$trace = array();
			for($i = 1; $i < count($bt); $i++) {
				if (empty($bt[$i]["file"]) || empty($bt[$i]["line"])) continue;
				$trace[] = "    ・ " . $bt[$i]["file"] . " on line " . $bt[$i]["line"];
			}
			$message .= PHP_EOL . implode(PHP_EOL, $trace);
		} else {
			$at = str_replace("\\", "/", $bt[1]["file"]) . " on line " . $bt[1]["line"];
			$o .= $at;
			$o .= " - ";
		}
		if (!empty($name)) {
			$o .= $name." : ";
		}

		$o .= print_r($message, true);
        self::printLine($o);
    }

    public static function error($message, $name="") {
        self::logging($message, "ERROR", $name);
    }

    public static function warn($message, $name="") {
        self::logging($message, "WARNING", $name);
    }

    public static function notice($message, $name="") {
        self::logging($message, "NOTICE", $name);
    }

    public static function info($message, $name="") {
        self::logging($message, "INFO", $name);
    }

    public static function debug($message, $name="") {
        self::logging($message, "DEBUG", $name);
    }

	private static function getLevel() {
		$CFG = null;
		if (function_exists("load_class")) {
			$CFG =& load_class('Config', 'core');
		}
		if (empty($CFG)) return 1;
		if (!isset($CFG->config["log_threshold"])) return 1;
		return $CFG->config["log_threshold"];
	}
	
    private static function printLine($message) {
        self::write($message);
    }

    private static function write($message) {
		if (defined("LOG_DIR") && is_dir(LOG_DIR)) {
			$logfile = LOG_DIR . self::$fileName;
		} else {
			$logfile = APPPATH  . "logs/" . self::$fileName;
		}
    	$fp = fopen($logfile, "a");
	    fwrite($fp, $message);
	    fclose($fp);
    }

    private static function getTime() {
		$microtime_full = microtime(TRUE);
		$microtime_short = sprintf("%06d", ($microtime_full - floor($microtime_full)) * 1000000);
		$date = new DateTime(date('Y-m-d H:i:s.'.$microtime_short, $microtime_full));
		return substr($date->format('Y-m-d H:i:s.u'), 0, 23);
    }
}
PbLogger::$fileName = "pb-" . date('Y-m-d') . ".log";
