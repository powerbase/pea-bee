<?php

class PbMessages extends PbEnum {
	const REQUIRED = "required_field";
	const OVERFLOW = "overflow";
	const NOT_INTEGER = "not_integer";
	const NOT_FLOAT = "not_float";
	
	
	public static function value($value) {
		$args = func_get_args();
		$self = new self($args[0]);
		$args[0] = $self->valueOf();
		return call_user_func(array("PbTextPerLang", "get"), $args);
	}
	
}
