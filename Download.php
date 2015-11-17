<?php

namespace YTDownloader;
use YTDownloader\Exceptions\YTDL as YTDL;

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
	 * Converted Video file name
	 * @var string
	 */
	private $_converted;

	/**
	 * Stores the default download location
	 * @var string
	 */
	private $_root;

	/**
	 * Stores the available video qualities
	 * @var string
	 */
	protected $_formats;

	public function __construct($url) {
		$this->_url = $url;
		$this->_root = dirname(__FILE__) . "/downloads/";
		$this->_videoId = Helper::getVideoId($this->_url);
		$this->_file = $this->_root . $this->_videoId . ".mp4";
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
		$this->_converted = $this->_root . $this->_videoId . ".{$fmt}";
		if (file_exists($this->_converted)) {
			return;
		}
		$this->haveVideo();
		Conversion::To($fmt, $this->_file, $this->_converted);
	}

	public function getUrl() {
		return $_url;
	}

	public function getVideoId() {
		return $this->_videoId;
	}

	public function setDownloadPath($path) {
		$this->_root = $path;
	}

	public function getDownloadPath() {
		return $this->_root;
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