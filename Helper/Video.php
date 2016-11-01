<?php
namespace YTDownloader\Helper;

class Video {
	protected static $AUDIO = [
		'-1' => 0, '249' => 1, '250' => 2, '140' => 3, '171' => 4, '251' => 5, '141' => 6
	];

	// Non - DASH Videos
	protected static $QUALITIES = [
		'144p' => ['code' => 17, 'ext' => '3gp'],
		'240p' => ['code' => 36, 'ext' => '3gp'],
		'360p' => ['code' => 18, 'ext' => 'mp4'],
		'720p' => ['code' => 22, 'ext' => 'mp4']
	];

	private function __construct() {
		// do nothing
	}

	private function __clone() {
		// do nothing
	}

	public static function getQualities() {
		return self::$QUALITIES;
	}

	/**
	 * Get youtube video ID from URL
	 *
	 * @param string $url Youtube URL of the video
	 * @return string|boolean Youtube video id or FALSE if none found. 
	 */
	public static function getId($url) {
		$pattern = 
		    '%^# Match any youtube URL
		    (?:https?://)?  # Optional scheme. Either http or https
		    (?:www\.)?      # Optional www subdomain
		    (?:             # Group host alternatives
		      youtu\.be/    # Either youtu.be,
		    | youtube\.com  # or youtube.com
		      (?:           # Group path alternatives
		        /embed/     # Either /embed/
		      | /v/         # or /v/
		      | /watch\?v=  # or /watch\?v=
		      )             # End path alternatives.
		    )               # End host alternatives.
		    ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
		    $%x'
		    ;
		$result = preg_match($pattern, $url, $matches);
		if (false !== $result) {
		    return $matches[1];
		}
		return false;
	}

	public static function getCode($str) {
		$code = (int) substr($str, 0, 3);

		return $code;
	}

	public static function compare($curr, $old) {
		if (self::$AUDIO[$curr] > self::$AUDIO[$old]) {
			return $curr;
		}
		return $old;
	}

	public function getExtension($filename) {
		$name = explode(".", $filename);
		$name = array_pop($name);

		return $name;
	}
}
