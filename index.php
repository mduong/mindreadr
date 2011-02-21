<html>
	<head>
		<title>MindReadr</title>
		
		<link type="text/css" rel="stylesheet" media="screen" href="jqtouch/jqtouch.min.css">
		<link type="text/css" rel="stylesheet" media="screen" href="themes/apple/theme.min.css">
		
		<!-- javascript -->
		<script src="jquery.1.3.2.min.js"></script>
		<script src="jqtouch/jqtouch.min.js"></script>
		<script src="jqtouch/jqtouch.transitions.js"></script>
		<script type="text/javascript">
			var jQT = new $.jQTouch({
				icon: 'images/appicon.png'
			});
		</script>
</head>
<body>
	<div id="home">
	  	<div class="toolbar">
			<h1>MindReadr</h1>
			<a class="button slideup" id="about_button" href="#about">About</a>
		</div>
	</div>
	<?php require_once('lib/MindReadrDb.php'); ?>
</body>
</html>
