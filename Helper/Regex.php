<?php

namespace YTDownloader\Helper;
use YTDownloader\Exceptions\Argument;
use YTDownloader\Exceptions\Core;

/**
 * Class to validate youtube video codes and media extensions
 */
class Regex {
	private static $_types = array(
		'videoCode' => array(
			'handler' => '_validateCode',
			'message' => 'Not a valid Youtube Video Code'
		),
		'extension' => array(
			'handler' => '_validateExtension',
			'message' => 'Not a valid media extension'
		)
	);

	private static function match($pattern, $string) {
		$return = preg_match($pattern, $string);
		if ($return === 0 || $return === false) {
			return false;
		}
		return true;
	}
	
	public static function validate($types = array()) {
		if (!is_array($types)) {
			throw new Argument('Invalid validation arguments');
		}
		self::_validate($types);
	}

	private static function _validate($types) {
		foreach ($types as $key => $value) {
			if (!array_key_exists($key, self::$_types)) {
				throw new Core('Validation type not found');
			}

			$func = self::$_types[$key]['handler'];
			$return = call_user_func(array(get_called_class(), $func), $value);

			if (!$return) {
				throw new Argument(self::$_types[$key]['message']);
			}
		}
	}

	private static function _validateCode($value) {
		return self::match("/^[0-9]{1,4}$/", $value);
	}

	private static function _validateExtension($value) {
		return self::match("/^[\w]{3,4}$/", $value);
	}
}
