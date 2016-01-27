<?php

namespace YTDownloader\Service;

use YTDownloader\Exceptions\YTDL as YTDL;
use YTDownloader\Helper\Video as Video;
use YTDownloader\Helper\Convert as Convert;

/**
 * This class will download youtube video
 * @param string $id Youtube VideoID of the video
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
	 * Downloaded Video File Name
	 * @var string
	 */
	private $_file;

	/**
	 * Stores the default download location
	 * @var string
	 */
	private static $_root = null;

	public function __construct($id) {
		$url = "https://www.youtube.com/watch?v=";
		$id = $url . Video::getId($id);
		
		if ($id === false) {
			throw new YTDL("Invalid Youtube ID");
		}

		if (!self::$_root) {
			$this->getDownloadPath();
		}
		$this->_url = $url . $id;
		$this->_videoId = $id;
	}

	protected function haveVideo($code = 22) {
		if (!file_exists($this->_file)) {
			$cmd = "youtube-dl -f {$code} -o ". $this->_file . " " . $this->_url;
			exec($cmd, $output, $return);

			if ($return !=0) {
				throw new YTDL("Can't download video");
			}
		}
	}

	protected function _getAvailableQualities() {
		$cmd = "youtube-dl -F --no-warnings ". $this->_url;
		exec($cmd, $output, $return);

		if ($return != 0) {
			throw new YTDL("Can't get available video formats");
		}

		foreach ($output as $key => $value) {
			if ($key < 5) {
				continue;
			}

			preg_match("/x([0-9]{3,4})$/", $value, $match);
			if ($match[1]) {
				$code = (int) substr($value, 0, 3);
				switch ($match[1]) {
					case '144':
						$this->_formats['144'] = $code;
						break;
					
					case '240':
						if (strstr($value, 'flv') !== FALSE) {
							$this->_formats['240']['flv'] = $code;
						} elseif (strstr($value, '3gp') !== FALSE) {
							$this->_formats['240']['3gp'] = $code;
						} else {
							$this->_formats['240']['mp4'] = $code;
						}
						break;

					case '360':
						if (strstr($value, 'mp4') !== FALSE) {
							$this->_formats['360']['mp4'] = $code;
						} elseif (strstr($value, 'webm') !== FALSE) {
							$this->_formats['360']['webm'] = $code;
						}
						break;
				}
			}
		}
		$this->_formats['720'] = 22;
	}

	public function convert($fmt = "mp3") {
		$this->_converted = self::$_root . $this->_videoId . ".{$fmt}";
		if (file_exists($this->_converted)) {
			return;
		}
		$this->haveVideo();
		Convert::To($fmt, $this->_file, $this->_converted);
	}

	public function getUrl() {
		return $this->_url;
	}

	public function getVideoId() {
		return $this->_videoId;
	}

	public function setDownloadPath($path) {
		self::$_root = $path;
	}

	public function getDownloadPath() {
		if (!isset(self::$_root)) {
			self::$_root = dirname(dirname(__FILE__)) . "/downloads/";
		}
		return self::$_root;
	}

	public function getFile() {
		return $this->_converted;
	}

	public function getVideo() {
		$this->haveVideo();
		return $this->_file;
	}

	/**
	 * Returns an array of available qualities
	 */
	public function availableQualities() {
		$this->_getAvailableQualities();
		$return = array();
		foreach ($this->_formats as $key => $value) {
			$return[$key."p"] = $value;
		}
		return $return;
	}
}
