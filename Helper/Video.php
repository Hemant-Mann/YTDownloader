<?php
namespace YTDownloader\Helper;

class Video {
	private function __construct() {
		// do nothing
	}

	private function __clone() {
		// do nothing
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
}
