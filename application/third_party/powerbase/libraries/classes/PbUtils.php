<?php

class PbUtils {

	private function __construct() {}

	public static function now($fmt="Y-m-d H:i:s") {
		return date($fmt);
	}

	public static function isVector(array $arr) {
		return array_values($arr) === $arr;
	}

	public static function startsWith($haystack, $needle){
		if (is_empty($needle)) return false;
		return strpos($haystack, $needle, 0) === 0;
	}

	public static function endsWith($haystack, $needle){
		if (is_empty($needle)) return false;
		$length = (strlen($haystack) - strlen($needle));
		if($length < 0) return FALSE;
		return strpos($haystack, $needle, $length) !== FALSE;
	}

	public static function contains($haystack, $needle){
		if (is_empty($needle)) return false;
		return strpos($haystack, $needle) !== FALSE;
	}

	public static function equals($s1, $s2){
		return $s1 === $s2;
	}

	public static function httpStatusCode($code) {
		if (isset($_SERVER["SERVER_PROTOCOL"])) {
			$protocol = $_SERVER["SERVER_PROTOCOL"];
		} else {
			$protocol = "HTTP/1.0";
		}
		switch ($code) {
			case 100:
				return $protocol." 100 Continue";
			case 101:
				return $protocol." 101 Switching Protocols";
			case 102:
				return $protocol." 102 Processing";
			case 200:
				return $protocol." 200 OK";
			case 201:
				return $protocol." 201 Created";
			case 202:
				return $protocol." 202 Accepted";
			case 203:
				return $protocol." 203 Non-Authoritative Information";
			case 204:
				return $protocol." 204 No Content";
			case 205:
				return $protocol." 205 Reset Content";
			case 206:
				return $protocol." 206 Partial Content";
			case 207:
				return $protocol." 207 Multi-Status";
			case 208:
				return $protocol." 208 Already Reported";
			case 226:
				return $protocol." 226 IM Used";
			case 300:
				return $protocol." 300 Multiple Choices";
			case 301:
				return $protocol." 301 Moved Permanently";
			case 302:
				return $protocol." 302 Found";
			case 303:
				return $protocol." 303 See Other";
			case 304:
				return $protocol." 304 Not Modified";
			case 305:
				return $protocol." 305 Use Proxy";
			case 307:
				return $protocol." 307 Temporary Redirect";
			case 308:
				return $protocol." 308 Permanent Redirect";
			case 400:
				return $protocol." 400 Bad Request";
			case 401:
				return $protocol." 401 Unauthorized";
			case 402:
				return $protocol." 402 Payment Required";
			case 403:
				return $protocol." 403 Forbidden";
			case 404:
				return $protocol." 404 Not Found";
			case 405:
				return $protocol." 405 Method Not Allowed";
			case 406:
				return $protocol." 406 Not Acceptable";
			case 407:
				return $protocol." 407 Proxy Authentication Required";
			case 408:
				return $protocol." 408 Request Timeout";
			case 409:
				return $protocol." 409 Conflict";
			case 410:
				return $protocol." 410 Gone";
			case 411:
				return $protocol." 411 Length Required";
			case 412:
				return $protocol." 412 Precondition Failed";
			case 413:
				return $protocol." 413 Request Entity Too Large";
			case 414:
				return $protocol." 414 Request-URI Too Long";
			case 415:
				return $protocol." 415 Unsupported Media Type";
			case 416:
				return $protocol." 416 Requested Range Not Satisfiable";
			case 417:
				return $protocol." 417 Expectation Failed";
			case 418:
				return $protocol." 418 I'm a teapot";
			case 422:
				return $protocol." 422 Unprocessable Entity";
			case 423:
				return $protocol." 423 Locked";
			case 424:
				return $protocol." 424 Failed Dependency";
			case 426:
				return $protocol." 426 Upgrade Required";
			case 500:
				return $protocol." 500 Internal Server Error";
			case 501:
				return $protocol." 501 Not Implemented";
			case 502:
				return $protocol." 502 Bad Gateway";
			case 503:
				return $protocol." 503 Service Unavailable";
			case 504:
				return $protocol." 504 Gateway Timeout";
			case 505:
				return $protocol." 505 HTTP Version Not Supported";
			case 506:
				return $protocol." 506 Variant Also Negotiates";
			case 507:
				return $protocol." 507 Insufficient Storage";
			case 509:
				return $protocol." 509 Bandwidth Limit Exceeded";
			case 510:
				return $protocol." 510 Not Extended";
			default:
				return $protocol." 500 Internal Server Error";
		}
	}
	
}
