<?php

namespace YTDownloader\Helper;
use YTDownloader\Exceptions\Format;

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
			$cmd = "ffmpeg -i {$inFile} -b:a 128K {$outFile}";
			exec($cmd); 
		} else {
			throw new Format("Unsupported format");
		}
	}
}
