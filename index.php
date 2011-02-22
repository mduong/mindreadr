<?php
	require_once 'lib/MindReadrDb.php';
	require_once 'lib/fb_config.php';
	
	$db = new MindReadrDb();
	
	session_start();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>MindReadr</title>
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a3/jquery.mobile-1.0a3.min.css" />
		<script src="http://code.jquery.com/jquery-1.5.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.0a3/jquery.mobile-1.0a3.min.js"></script>
	</head>
	<body>
		<div data-role="page">
			<div data-role="header">
				<h1>MindReadr</h1>
			</div>
			
			<div data-role="content">
				<ul data-role="listview">
					<li>
				<?php
					if ($_SESSION['me']) {
						echo 'Welcome back ' . $_SESSION['me']['first_name'] . '!';
					} else {
						try {
							$me = $facebook->api('/me');
							if (!$db->userExists($me['id'])) {
								$db->createUser($me);
								$friends = $facebook->api('/me/friends');
								$db->addFriends($me['id'], $friends["data"]);
								echo 'Welcome to MindReadr, ' . $me['first_name'] . '!';
							} else {
								echo 'Welcome back ' . $me['first_name'] . '!';
							}
							$_SESSION['me'] = $me;
						} catch (FacebookApiException $e) {
							error_log($e);
						}
					}
				?>
					</li>
				</ul>
				<br />
				<a href="views/topics.php?play=friends" data-role="button">Play with Friends</a>
				<a href="views/topics.php?play=instant" data-role="button">Play Instantly</a>
				<a href="views/friends.php" data-role="button">Friends</a>
				<a href="views/topics.php" data-role="button">Topics</a>
			</div>
			
			<div data-role="footer">
				<h4>EDUC 196X</h4>
			</div>
		</div>
	</body>
</html>