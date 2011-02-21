<?php

	require_once 'lib/MindReadrDb.php';
	require 'lib/facebook.php';
	
	$facebook = new Facebook(array(
		'appId' => '165478150170952',
		'secret' => '954447415b7f3d150c4772af1a66b4df',
		'cookie' => true,
	));
	
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
				<?php
					if ($facebook->getSession()) {
						if ($_SESSION['me']) {
							echo '<a href="views/friends.php" data-role="button">Friends</a>';
							echo '<a href="views/topics.php" data-role="button">Topics</a>';
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
				?>
				<a href="views/friends.php" data-role="button">Friends</a>
				<a href="views/topics.php" data-role="button">Topics</a>
				<?php
							} catch (FacebookApiException $e) {
								error_log($e);
							}
						}
					} else {
						echo '<a href="' . $facebook->getLoginUrl(array("req_perms" => "email")) . '" data-role="button">Login</a>';
					}
				?>
			</div>
			
			<div data-role="footer">
				<h4>EDUC 196X</h4>
			</div>
		</div>
	</body>
</html>