<?php

require '../autoloader.php';

$url = 'https://www.youtube.com/watch?v=oeCihv9A3ac';

$track = 'Eminem - Phenomenal';
$download = new YTDownloader\Service\Download($url);

YTDownloader\Helper\Convert::$quality = "256K";
$file = $download->convert();

$file = YTDownloader::getDownloadPath() . $file;
header('Content-type: audio/mpeg');
header('Content-length: ' . filesize($file));
header('Content-Disposition: attachment; filename="'.$track.'.mp3"');
header("Content-Transfer-Encoding: binary"); 
header("Content-Type: audio/mpeg, audio/x-mpeg, audio/x-mpeg-3, audio/mpeg3");

readfile($file);