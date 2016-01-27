<?php
require '../autoloader.php';

$check = $_POST['check'];
if (isset($check) && $check == "submitForm") {
	$action = $_POST['action'];
	$videoId = $_POST['videoId'];
	
	$youtube = new YTDownloader\Download($videoId);
	$response = array();
	switch ($action) {
		case 'all':
			$response = $youtube->availableQualities();
			break;
		
		case 'best':
			$file = $youtube->getVideo();
			$response['success'] = true;
			$response['file'] = $file;
			break;
	}
	echo json_encode($response);
}
?>