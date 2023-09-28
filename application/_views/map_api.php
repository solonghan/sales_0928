<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
	<script>
		$.ajax({
			url: 'https://geocoder.ls.hereapi.com/6.2/geocode.json',
			type: 'GET',
			dataType: 'jsonp',
			jsonp: 'jsoncallback',
			data: {
				searchtext: '台北市民權西路104號10樓',
				gen: '',
				apiKey: 'H6XyiCT0w1t9GgTjqhRXxDMrVj9h78ya3NuxlwM7XUs'
			},
			success: function (data) {
				alert(JSON.stringify(data));
			}
		});
	</script>
</body>
</html>