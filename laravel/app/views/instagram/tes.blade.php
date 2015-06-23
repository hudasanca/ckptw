<!DOCTYPE html>
<html>
<head>
	{{HTML::script(URL::asset('assets/js/jquery.min.js'))}}
</head>
<body>
	<button id="get">Get</button>
	<div id="result"></div>
	<script>
	$(document).ready(function(){
		$('#getInstagram').click(function(){
			$.ajax({
				'url':'http://belajar.dev/get/photos/from/instagram',
				'method':'get',
				'success':function(data,status){
					$('#result').html(data);
				}
			});
		});
	});
	</script>
</body>
</html>