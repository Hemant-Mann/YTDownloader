# YTDownloader

It is library for downloading and converting youtube videos to desired formats

## Dependencies
### Youtube-dl
```bash
# Linux/Mac Users
sudo wget https://yt-dl.org/downloads/2016.05.10/youtube-dl -O /usr/local/bin/youtube-dl
sudo chmod a+rx /usr/local/bin/youtube-dl
```

### FFMPEG
```bash
# For Ubuntu 15.10 above
sudo apt-get install ffmpeg
```

## Setup
```bash
# Linux/Unix Based System
git clone https://github.com/Hemant-Mann/YTDownloader.git
cd YTDownloader
mkdir downloads
chmod 777 downloads/
```

## Usage
```php
<?php
require 'autoloader.php';
use YTDownloader\Service\Download as Downloader;

$yid = 'YykjpeuMNEk'; // pass the youtube video ID
$url = "https://www.youtube.com/watch?v=";

try {
  $ytdl = new Downloader($url . $yid);
  
  
  // download the video
  $quality = 22; $extension = 'mp4';
  
  // $file = $ytdl->download($quality, $extension);
  
  // Make mp3 from the video
  $file = $ytdl->convert();
  
  $file = Downloader::getDownloadPath() . $file;
  
  var_dump($file);
} catch (\Exception $e) {
  echo print_r($e, true);
}

?>
