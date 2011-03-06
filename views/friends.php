<?php 
	require_once '../lib/MindReadrDb.php';
	require_once '../lib/fb_config.php';
	
	$db = new MindReadrDb();
	
	session_start();
?>

<!DOCTYPE html> 
<html> 
	<head> 
	<?php
		if ($_GET["topic_id"])
			echo "<title>Select a friend</title>";
		else
			echo "<title>Friends</title>";
	?> 
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a3/jquery.mobile-1.0a3.min.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.3.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/mobile/1.0a3/jquery.mobile-1.0a3.min.js"></script>
</head> 
<body> 

<div data-role="page">

	<div data-role="header">
			<?php
				if ($_GET["topic_id"])
					echo "<h1>Select a friend</h1>";
				else
					echo "<h1>Friends</h1>";
			?>
		<a href="#" data-rel="back" data-icon="arrow-l">Back</a>
		<a href="/~mduong/ed196x/" data-role="button" data-icon="home" data-iconpos="notext"></a>
	</div><!-- /header -->

	<div data-role="content">
		<ul data-role="listview" role="listbox">
			<?php
				if ($_GET["topic_id"]) {
					$friends = $db->getPossibleOpponents($_SESSION["me"]["id"]);
				} else {
					$friends = $db->getFriends($_SESSION["me"]["id"]);
				}
				$friends = json_decode($friends);
				$last_letter = "";
				foreach ($friends as $friend) {
					$parameters = '/' . $friend->{'friend2_id'};
					$fb_friend = $facebook->api($parameters);
					$letter = substr($fb_friend["name"], 0, 1);
					if ($letter != $last_letter) {
						echo '<li data-role="list-divider">' . $letter . '</li>';
						$last_letter = $letter;
					}
					if ($_GET["topic_id"]) {
						echo '<li onclick="$.mobile.changePage(\'views/start.php?topic_id=' . $_GET["topic_id"] . '&opponent_id=' . $fb_friend["id"] . '\');" class="ul-li-has-fb-img">';
						echo '<img src="https://graph.facebook.com/' . $friend->{'friend2_id'} . '/picture" />';
						echo '<h3>' . $fb_friend["name"] . '</h3>';
					} else {
						echo '<li class="ul-li-has-fb-img">';
						echo '<img src="https://graph.facebook.com/' . $friend->{'friend2_id'} . '/picture" />';
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