<!DOCTYPE html>
<html>
<head>
	<title>Youtube Downloader</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
</head>
<body>
<h1 class="page-heading text-center jumbotron">Download Any Video</h1>
<form action="action.php" id="ytForm" method="get" class="form-inline text-center">
  <div class="form-group">
    <label for="url">Youtube URL:</label>
    <input type="text" name="url" class="form-control" id="url" required="" placeholder="Enter Youtube Video URL">
  </div>
  <div class="input-group">
  	<label for="Action">Action:</label>
  	<label class="radio-inline">
  	  <input type="radio" name="action" value="all" checked=""> See Available formats
  	</label>
  	<label class="radio-inline">
  	  <input type="radio" name="action" value="best"> Best Quality Download
  	</label>
  </div>
  <input type="hidden" name="check" value="submitForm">
  <button type="submit" class="btn btn-default">Submit</button>
</form>

<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script>
	$(document).ready(function() {
		$("#ytForm").on("submit", function (e) {
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