<!doctype html>
<html lang="en">
  <head>
	<title>Antrian</title>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
	
</head>
  <body>
	 <div id="antrian"></div>

	 <script>
		 $(document).ready(function(){
			let timerId = setInterval(() => $("#antrian").load("<?= base_url('antrian/count');?>"), 2000);
		 });
		 
	</script>
	<!-- Optional JavaScript -->
</body>
</html>
