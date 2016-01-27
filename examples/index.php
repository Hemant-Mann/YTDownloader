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
    					    <div id="results"></div>

    					    <div class="form-group" id="chooseAction">
    					    	<label for="Action">Action:</label>
    					    	<input type="radio" name="action" value="qualities" checked=""> See Available formats
    					    	<input type="radio" name="action" value="downloadBest"> Best Quality download
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
    $(document).ready(function() {
        $("#ytForm").on("submit", function(e) {
            e.preventDefault();
            var display = $("#results");
            display.html('');
            $("#submitBtn").html('<p><i class="fa fa-spinner fa-spin"></i> Loading</p>');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
            })
            .done(function(data) {
            	var results = [], formats = [];
            	$("#submitBtn").html('<button type="submit" class="btn btn-default">Submit</button>');

            	if (!data.download) {
        			data = JSON.parse(data);
        			// eg: {"144p":{"3gp":17},"240p":{"3gp":36,"flv":5},"360p":{"webm":43,"mp4":18},"720p":{"mp4":22}}
        			for (f in data) {
        				formats = [];
        				for (v in data[f]) {
        					formats.push({type: v, code: data[f][v]});
        				}
        				results.push({
        					quality: f,
        					formats: formats
        				});
        			}
        		    console.log(results);

        		    display.html('<ul>');
        		    results.forEach(function (el) {
        		    	display.append('<li><strong>' + el.quality + '</strong><div class="form-group">');
        		    	el.formats.forEach(function (fmt) {
        		    		display.append(' <input type="radio" name="quality" value="' + fmt.code + '"> ' + fmt.type);
        		    	});
        		    	display.append('</div></li>');
        		    });
        		    
        		    display.append('</ul>');
        		    $("#chooseAction").append('<input type="radio" name="action" value="download"> Select and Download');
            	} else {
            		window.location.href = 'download.php?id=' + data.file;
            	}
            })
            .fail(function() {
                console.log("error");
            });
        });
    });
    </script>
</body>

</html>
