<?php 
	require_once '../lib/MindReadrDb.php';
	require_once '../lib/fb_config.php';
	
	$db = new MindReadrDb();
	
	session_start();
?>

<!DOCTYPE html> 
<html> 
	<head> 
	<title>Select a teammate</title> 
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a3/jquery.mobile-1.0a3.min.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.3.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/mobile/1.0a3/jquery.mobile-1.0a3.min.js"></script>
</head> 
<body> 

<div data-role="page">

	<div data-role="header">
		<h1>Select a teammate</h1>
		<a href="#" data-rel="back" data-icon="arrow-l">Back</a>
	</div><!-- /header -->

	<div data-role="content">
		<ul data-role="listview" role="listbox">
			<?php
				$friends = $db->getFriends($_SESSION["me"]["id"]);
				$friends = json_decode($friends);
				foreach ($friends as $friend) {
					$parameters = '/' . $friend->{'friend2_id'};
					$fb_friend = $facebook->api($parameters);
					echo '<li>';
					echo '<img src="https://graph.facebook.com/' . $friend->{'friend2_id'} . '/picture" />';
					if ($_GET["topic"]) {
						echo '<a href="teams.php?topic=' . $_GET["topic"] . '&friend=' . $fb_friend["id"] . '">' . $fb_friend["name"] . '</a>';
					} else {
						echo '<h3>' . $fb_friend["name"] . '</h3>';
					}
					echo '</li>';
				}
			?>
		</ul>
	</div><!-- /content -->

	<div data-role="footer">
		<h4>EDUC 196X</h4>
	</div><!-- /footer -->
</div><!-- /page -->

</body>
</html>