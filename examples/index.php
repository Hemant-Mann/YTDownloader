<!DOCTYPE html>
<html>

<head>
    <title>Youtube Downloader</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>

<body>
    <h1 class="page-heading text-center jumbotron">Download Any Video</h1>
    <div class="container">
    	<div class="row">
    		<div class="col-lg-8 col-lg-offset-2">
    			<div class="panel panel-default">
    				<div class="panel-header"></div>
    				<div class="panel-body">
    					<form action="action.php" id="ytForm" method="get" class="form text-center" role="form">
    					    <div class="form-group">
    					        <label for="url">Youtube ID:</label>
    					        <div class="row">
    					        	<div class="col-lg-5">
    					        		<input type="text" size="32" disabled="" value="https://www.youtube.com/watch?v=" class="form-control">
    					        	</div>
    					        	<div class="col-lg-3">
    					        		<input type="text" size="14" name="videoId" class="form-control" id="url" required="" placeholder="Enter Youtube Video URL">
    					        	</div>
    					        </div>
    					    </div>
    					    <div class="form-group">
    					    	<label for="Action">Action:</label>
    					    	<input type="radio" name="action" value="all" checked=""> See Available formats
    					    	<input type="radio" name="action" value="best" checked=""> Best Quality download
    					    </div>
    					    
    					    <input type="hidden" name="check" value="submitForm">
    					    <button type="submit" class="btn btn-default">Submit</button>
    					</form>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script>
    $(document).ready(function() {
        $("#ytForm").on("submit", function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
            })
            .done(function(data) {
                console.log(data);
            })
            .fail(function() {
                console.log("error");
            });
        });
    });
    </script>
</body>

</html>
