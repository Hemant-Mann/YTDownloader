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
use YTDownloader\Helper\Convert as Convert;

$url = "https://www.youtube.com/watch?v=YykjpeuMNEk";

try {
  // $opts = ['path' => '/home/user/Download', 'bitrate' => '128K'];
  // $ytdl = new Downloader($url, $opts);

  $ytdl = new Downloader($url);
  
  // Get Available Qualities
  // $q = $ytdl->availableQualities();
  
  // Dont need to manually Mention the quality code just pass the human
  // readable string - '144p', '240p' etc
  // $file = $ytdl->download(22, 'mp4'); (prev version)

  // Current and more readable
  $mp4File = $ytdl->convert('mp4', ['type' => 'video', 'quality' => '360p', 'fullPath' => true]);
  // $mp4File = $ytdl->convert('mp4', ['type' => 'video', 'quality' => '244p']);
  
  $mp3File = $ytdl->convert('mp3', ['type' => 'audio', 'fullPath' => true]);
  
  // prev Version
  // $mp3File = Downloader::getDownloadPath() . $mp3File;

  // Current Version -- Pass fullPath to convert function to get fullpath of the downloaded file
  
  var_dump($mp3File);
  var_dump($mp4File);
} catch (\Exception $e) {
  echo print_r($e, true);
}
