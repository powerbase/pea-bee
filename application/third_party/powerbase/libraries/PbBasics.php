<?php

class PbBasics {}
/*
 * global functions definition.
 */
if (!function_exists('pr')) {
	/**
	 * print_r() convenience function.
	 *
	 * In terminals this will act similar to using print_r() directly, when not run on cli
	 * print_r() will also wrap <pre> tags around the output of given variable. Similar to debug().
	 *
	 * @param mixed $var Variable to print out.
	 * @return void
	 * @see debug()
	 * @link http://book.cakephp.org/3.0/en/core-libraries/global-constants-and-functions.html#pr
	 */
	function pr($var)
	{
		$template = PHP_SAPI !== 'cli' ? '<pre class="pr" style="font-family:sans-serif;white-space: pre-wrap;word-wrap: break-word;overflow: auto;font-size:80%%; margin: 10px; padding: 10px; border: 1px solid #D0D0D0; box-shadow: 0 0 8px #D0D0D0;">%s</pre>' : "\n%s\n\n";
		printf($template, trim(print_r($var, true)));
	}
}

if (!function_exists('h')) {
    /**
     * Convenience method for htmlspecialchars.
     *
     * @param string|array|object $text Text to wrap through htmlspecialchars. Also works with arrays, and objects.
     *    Arrays will be mapped and have all their elements escaped. Objects will be string cast if they
     *    implement a `__toString` method. Otherwise the class name will be used.
     * @param bool $double Encode existing html entities.
     * @param string|null $charset Character set to use when escaping. Defaults to config value in `mb_internal_encoding()`
     * or 'UTF-8'.
     * @return mixed Wrapped text.
     * @link http://book.cakephp.org/3.0/en/core-libraries/global-constants-and-functions.html#h
     */
    function h($text, $double = true, $charset = null)
    {
        if (is_string($text)) {
            //optimize for strings
        } elseif (is_array($text)) {
            $texts = [];
            foreach ($text as $k => $t) {
                $texts[$k] = h($t, $double, $charset);
            }
            return $texts;
        } elseif (is_object($text)) {
            if (method_exists($text, '__toString')) {
                $text = (string)$text;
            } else {
                $text = '(object)' . get_class($text);
            }
        } elseif (is_bool($text)) {
            return $text;
        }

        static $defaultCharset = false;
        if ($defaultCharset === false) {
            $defaultCharset = mb_internal_encoding();
            if ($defaultCharset === null) {
                $defaultCharset = 'UTF-8';
            }
        }
        if (is_string($double)) {
            $charset = $double;
        }
        return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, ($charset) ? $charset : $defaultCharset, $double);
    }
}

if (!function_exists('is_empty')) {
	function is_empty($val) {
		if (isset($val)) {
			if ($val === null) return true;
			if (is_array($val)) return empty($val);
			if (is_object($val)) return empty($val);
			if (is_string($val) && trim($val) == "") return true;
			if (is_string($val) && (int)$val === 0) return false;
			return empty($val);
		}
		return true;
	}
}


