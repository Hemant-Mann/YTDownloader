<?php

namespace YTDownloader\Service;

use YTDownloader\Exceptions\YTDL as YTDL;
use YTDownloader\Helper\Video as Video;
use YTDownloader\Helper\Convert as Convert;
use YTDownloader\Helper\Regex as Regex;

/**
 * This class will download youtube video
 * @param string $url Youtube Video URL
 */
class Download {
	/**
	 * Stores the Youtube URL
	 * @var string
	 */
	private $_url;

	/**
	 * Stores the Youtube Video ID
	 * @var string
	 */
	private $_videoId;

	/**
	 * Stores different video formats
	 * @var array
	 */
	private $_formats;

	/**
	 * Downloaded Video File Name
	 * @var string
	 */
	private $_file;

	/**
	 * Stores the default download location
	 * @var string
	 */
	private static $_location = null;

	public function __construct($url) {
		$id = Video::getId($url);
		$url = "https://www.youtube.com/watch?v="; // manually fix the url
		
		if ($id === false) {
			throw new YTDL("Invalid Youtube ID");
		}

		if (!self::$_location) {
			self::getDownloadPath();
		}
		$this->_url = $url . $id;
		$this->_videoId = $id;
	}

	/**
	 * Downloades the video using youtube-dl
	 * @return string Filename of the downloaded file
	 * Ensure "youtube-dl" is in your $PATH
	 */
	protected function _download($code = 18, $extension = "mp4") {
		$fileName = $this->_videoId . "-{$code}" . ".{$extension}";
		$file = self::$_location . $fileName;

		if (!file_exists($file)) {
			$cmd = "youtube-dl -f {$code} -o $file " . $this->_url;
			exec($cmd, $output, $return);

			if ($return != 0) {
				throw new YTDL("Can't download video");
			}
		}
		$this->_file = $file;
		return $fileName;
	}

	/**
	 * Executes the shell command for finding available video formats and
	 * parses the result using regular expression
	 * Ensure "youtube-dl" is in your $PATH
	 */
	protected function _availableQualities() {
		$cmd = "youtube-dl -F --no-warnings ". $this->_url;
		exec($cmd, $output, $return);
		
		if ($return != 0) {
			throw new YTDL("Can't get available video formats");
		}

		foreach ($output as $key => $value) {
			if ($key < 5) continue;

			preg_match("/x([0-9]{3,4})/", $value, $match);

			if (isset($match[1])) {
				$code = (int) substr($value, 0, 3);

				if (!preg_match("/(DASH\s(video|audio))|only/", $value)) {
					preg_match("/^[0-9]{0,3}\s*(\w+)/", $value, $f);
					$this->_formats[$match[1]][$f[1]] = $code;	
				}
			}
		}
	}

	/**
	 * Converts the video to given format
	 */
	public function convert($fmt = "mp3") {
		Regex::validate(array('extension' => $fmt));
		$filename = $this->_videoId . ".{$fmt}";
		$this->_converted = self::$_location . $filename;
		if (!file_exists($this->_converted)) {
			$this->_download();
			Convert::To($fmt, $this->_file, $this->_converted);
			unlink($this->_file); // remove the video used for converting
		}
		return $filename;
	}

	public function getVideoId() {
		return $this->_videoId;
	}

	public static function setDownloadPath($path) {
		if (!is_dir($path)) {
			throw new YTDL("Invalid Download Location Specified!!");
		}
		self::$_location = $path;
	}

	public static function getDownloadPath() {
		if (!isset(self::$_location)) {
			self::$_location = dirname(dirname(__FILE__)) . "/downloads/";
		}
		return self::$_location;
	}

	/**
	 * @return array Returns an array of available qualities
	 */
	public function availableQualities() {
		$this->_availableQualities();
		$return = array();
		foreach ($this->_formats as $key => $value) {
			$return[$key."p"] = $value;
		}
		return $return;
	}

	/**
	 * downloads a video of given quality
	 * @param int $code Youtube Video code
	 * @param string $extension Video extension
	 * @return string Returns the name of the downloaded file
	 */
	public function download($code, $extension) {
		Regex::validate(array(
			'videoCode' => $code,
			'extension' => $extension
		));
		return $this->_download($code, $extension);
	}
}
