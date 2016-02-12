<?php
require '../autoloader.php';

$check = $_POST['check']; $action = null; $response = array(); $videoId = null;
if (isset($check) && $check == "submitForm") {
    $action = $_POST['action'];
    $videoId = $_POST['videoId'];
    
    $youtube = new YTDownloader\Service\Download($videoId);

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

        if (isset($response['file'])) {
            header('Location: download.php?id='. $response['file']);
            exit();
        }
    } catch (Exception $e) {
        $response["error"] = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Youtube Downloader</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
</head>

<body>
    <h1 class="page-heading text-center jumbotron">Download Any Video</h1>
    <div class="container">
    	<div class="row">
    		<div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2">
    			<div class="panel panel-default">
    				<div class="panel-header"></div>
    				<div class="panel-body">
    					<form action="" id="ytForm" method="post" class="form text-center" role="form">
    					    <div class="form-group">
    					        <label for="url">Youtube ID:</label>
    					        <div class="containter">
    					        	<div class="row">
    					        		<div class="col-lg-6 col-md-6">
    					        			<input type="text" disabled="" value="https://www.youtube.com/watch?v=" class="form-control">
    					        		</div>
    					        		<div class="col-lg-4 col-md-4">
    					        			<input type="text" name="videoId" class="form-control" id="url" required="" placeholder="Enter Youtube Video ID" value="<?php echo (isset($videoId) ? $videoId : ""); ?>">
    					        		</div>
    					        	</div>
    					        </div>
    					    </div>
    					    <div id="results" class="form-group">
                            <?php if (count($response) > 0 && !isset($response["error"])): ?>
                                <?php foreach ($response as $key => $value): ?>
                                    <ul class="list-unstyled">
                                        <li>
                                            <strong><?php echo $key; ?>:</strong>
                                            <?php foreach ($value as $k => $val): ?>
                                                <input type="radio" name="quality" value="<?php echo $val; ?>" required=""> <?php echo $k; ?>
                                            <?php endforeach ?>
                                        </li>
                                    </ul>
                                <?php endforeach ?>
                            <?php endif ?>               
                            </div>

    					    <div class="form-group" id="chooseAction">
    					    	<label for="Action">Action:</label>
    					    	<input type="radio" name="action" value="downloadBest"> Best Quality download
                                <?php if (isset($action) && $action == "qualities") { ?>
                                <input type="radio" name="action" value="download"> Download
                                <?php } else { ?>
                                <input type="radio" name="action" value="qualities" checked=""> See Available formats
                                <?php } ?>
    					    </div>
    					    
    					    <input type="hidden" name="check" value="submitForm">
    					    <div id="submitBtn">
    					    	<button type="submit" class="btn btn-default">Submit</button>
    					    </div>
    					</form>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script>
    </script>
</body>

</html>
