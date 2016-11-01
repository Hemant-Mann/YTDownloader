<?php

namespace YTDownloader\Helper;
use YTDownloader\Exceptions\Argument;
use YTDownloader\Exceptions\Core;

/**
 * Static Class to Convert Videos to desired format 
 * with the help of ffmpeg
 */
class Convert {
	private static $_supportedFormats = array(
		'audio' => array(
			'mp2', 'mp3', '3gp'
		),
		'video' => array(
			'avi', 'flv', 'mp4', '3gp', 'webm'
		)
	);

	/**
	 * @values: 128K | 192K | 256K
	 * @var string
	 */
	public static $bitrate = "192K";

	private function __construct() {
		// do nothing
	}

	private function __clone() {
		// do nothing
	}

	public static function toAudio($extension, $inFile, $outFile) {
		if (in_array($extension, self::$_supportedFormats['audio'])) {
			$cmd = "ffmpeg -i {$inFile} -vn -ab ". self::$bitrate ." -ar 44100 -y {$outFile}";
			exec($cmd, $output, $return);
			if ($return !== 0) {
				throw new Core("Unable to convert the file");
			}
		} else {
			throw new Argument('Unsupported $extension argument');
		}
	}

	/**
	 * Converts the video of one format to the other format. Tested for outfile --> "*.mp4"
	 * @param  string $extension Extension of the final converted video
	 * @param  string $inFile    Input File (Full path)
	 * @param  string $outFile   Output File (full path)
	 */
	public static function toVideo($extension, $inFile, $outFile) {
		if (in_array($extension, self::$_supportedFormats['video'])) {

			if (Video::getExtension($inFile) === $extension) {
				copy($inFile, $outFile);
				return true;
			}

			$cmd = 'ffmpeg -i '. $inFile .' -acodec libmp3lame -ar 44100 ' . $outFile;
        	exec($cmd, $output, $return);

        	if ($return !== 0) {
        		throw new Core("Unable to convert the file!!");
        	}
		} else {
			throw new Argument('Unsupported $extension argument');
		}
	}
}
