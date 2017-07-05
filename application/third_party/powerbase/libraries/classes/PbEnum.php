<?php

/**
 * Class PbEnum
 * 
 * Source: http://qiita.com/Hiraku/items/71e385b56dcaa37629fe
 * License: https://creativecommons.org/publicdomain/zero/1.0/deed.en
 */
abstract class PbEnum {
	private $scalar;

	public function __construct($value) {
		$ref = new ReflectionObject($this);
		$consts = $ref->getConstants();
		if (!in_array($value, $consts, true)) {
			throw new PbException("Invalid argument.");
		}
		$this->scalar = $value;
	}

	final public static function __callStatic($label, $args) {
		$class = get_called_class();
		$const = constant("$class::$label");

		return new $class($const);
	}

	final public function valueOf() {
		return $this->scalar;
	}

	final public function __toString() {
		return (string)$this->scalar;
	}
}
