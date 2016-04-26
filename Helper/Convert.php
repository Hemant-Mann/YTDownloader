<?php

namespace YTDownloader\Helper;
use YTDownloader\Exceptions\Argument;

class Convert {
	private static $_supportedFormats = array(
		'audio' => array(
			'mp2', 'mp3', '3gp'
		),
		'video' => array(
			'avi', 'flv'
		)
	);

	private function __construct() {
		// do nothing
	}

	private function __clone() {
		// do nothing
	}

	public static function To($fmt, $inFile, $outFile) {
		if (in_array($fmt, self::$_supportedFormats['audio']) || in_array($fmt, self::$_supportedFormats['video'])) {
			$cmd = "ffmpeg -i {$inFile} -b:a 192K {$outFile}";
			exec($cmd, $output, $return);
			if ($return !== 0) {
				throw new \YTDownloader\Exceptions\Core("Unable to convert the file");
			}
		} else {
			throw new Argument('Unsupported $format argument');
		}
	}
}
