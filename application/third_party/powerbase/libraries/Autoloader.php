<?php
/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014 Rob Dunham
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 * Simple Recursive Autoloader
 *
 * A simple autoloader that loads class files recursively starting in the directory
 * where this class resides.  Additional options can be provided to control the naming
 * convention of the class files.
 *
 * @package Autoloader
 * @license http://opensource.org/licenses/MIT  MIT License
 * @author  Rob Dunham <contact@robunham.info>
 * @see https://gist.github.com/Nilpo/5de133d2ab7a025bebeb
 */
class Autoloader {
	/**
	 * File extension as a string. Defaults to ".php".
	 */
	private static $fileExt = '.php';

	/**
	 * The top level directory where recursion will begin. Defaults to the current
	 * directory.
	 */
	private static $pathTop;

	/**
	 * Class file names.
	 */
	private static $classes = null;

	/**
	 * Autoload function for registration with spl_autoload_register
	 *
	 * Looks recursively through project directory and loads class files based on
	 * filename match.
	 *
	 * @param string $className
	 */
	public static function loader($className) {
		if(class_exists($className, false) || interface_exists($className, false)) return;
		$filename = $className . self::$fileExt;
		if (self::$classes === null) {
			$directory = new RecursiveDirectoryIterator(self::$pathTop, RecursiveDirectoryIterator::SKIP_DOTS);
			$classes = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::LEAVES_ONLY);
			foreach($classes as $file) self::$classes[] = $file;
		}
		if (!is_array(self::$classes)) self::$classes = array(); 
		reset(self::$classes);
		foreach(self::$classes as $file) {
			if (strtolower($file->getFilename()) === strtolower($filename)) {
				if ($file->isReadable()) require $file->getPathname();
				break;
			}
		}
	}

	/**
	 * Sets the $fileExt property
	 *
	 * @param string $fileExt The file extension used for class files.  Default is "php".
	 */
	public static function setFileExt($fileExt) {
		static::$fileExt = $fileExt;
	}

	/**
	 * Sets the $path property
	 *
	 * @param string $path The path representing the top level where recursion should
	 *                     begin. Defaults to the current directory.
	 */
	public static function setPath($path) {
		static::$pathTop = $path;
	}

}
Autoloader::setPath(dirname(__FILE__) . "/classes");
spl_autoload_register('Autoloader::loader');
