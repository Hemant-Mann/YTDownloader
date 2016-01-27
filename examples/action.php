<?php
require '../autoloader.php';

$check = $_POST['check'];
if (isset($check) && $check == "submitForm") {
	$action = $_POST['action'];
	$videoId = $_POST['videoId'];
	
	$youtube = new YTDownloader\Service\Download($videoId);
	$response = array();

	try {
		switch ($action) {
			case 'qualities':
				$response = $youtube->availableQualities();
				break;
			
			case 'downloadBest':
				$file = $youtube->getVideo();
				$response['download'] = true;
				$response['file'] = $file;
				break;

			case 'download':
				$quality = $_POST['quality'];
				$file = $youtube->download($quality, 'mp4');
				$response['download'] = true;
				$response['file'] = $file;
				break;
		}
	} catch (Exception $e) {
		$response["error"] = $e->getMessage();
	}
	
	echo json_encode($response);
}
?>