<?php

	require 'lib/facebook.php';
	
	$facebook = new Facebook(array(
		'appId' => '165478150170952',
		'secret' => '954447415b7f3d150c4772af1a66b4df',
		'cookie' => true,
	));
	
	$db = new MindReadrDb();

?>

<html>
	<head>
		<title>MindReadr</title>
		<script type="text/javascript" src="http://www.google.com/jsapi"></script>
		<script type="text/javascript">google.load("jquery", "1.4.2");</script>
		<script src="jqtouch/jqtouch.min.js" type="application/x-javascript" charset="utf-8"></script>
		<style type="text/css" media="screen">@import "jqtouch/jqtouch.min.css";</style>
		<style type="text/css" media="screen">@import "themes/jqt/theme.min.css";</style>
		<script type="text/javascript">
			$.jQTouch({
					icon: 'jqtouch.png',
					statusBar: 'black-translucent',
					preloadImages: [
					'themes/jqt/img/chevron.png',
					'themes/jqt/img/back_button_clicked.png',
					'themes/jqt/img/button_clicked.png'
					]
			});
		</script>
	</head>
<body>
	<div id="home">
	  	<div class="toolbar">
			<h1>MindReadr</h1>
			<a class="button slideup" id="about_button" href="#about">About</a>
		</div>
		<?php
			if ($facebook->getSession()) {
				try {
					$me = $facebook->api('/me');
					if (!$db->userExists($me['id'])) {
						$db->createUser($me);
					}
				} catch (FacebookApiException $e) {
					error_log($e);
				}
			} else {
				echo '<ul>';
				echo '<li><a href="' . $facebook->getLoginUrl(array("req_perms" => "email")) . '" target="_blank">Login</a></li>';
				echo '</ul>';
			}
		?>
	</div>
	<?php require_once('lib/MindReadrDb.php'); ?>
</body>
</html>
