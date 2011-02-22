<?php 
	require_once '../lib/MindReadrDb.php';
	require_once '../lib/fb_config.php';
	
	$db = new MindReadrDb();
	
	session_start();
	
	$user1_id = $_SESSION["me"]["id"];
	$user2_id = $_GET["friend"];
	
	if (!($team_id = $db->teamExists($user1_id, $user2_id))) {
		$asdf = "false";
		$db->createTeam($user1_id, $user2_id);
		$team_id = $db->teamExists($user1_id, $user2_id);
	}
?>

<!DOCTYPE html> 
<html> 
	<head> 
	<title>Select an opponent</title> 
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a3/jquery.mobile-1.0a3.min.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.3.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/mobile/1.0a3/jquery.mobile-1.0a3.min.js"></script>
</head> 
<body> 

<div data-role="page">

	<div data-role="header">
		<h1>Select an opponent</h1>
		<a href="#" data-rel="back" data-icon="arrow-l">Back</a>
	</div><!-- /header -->

	<div data-role="content">	
		<ul data-role="listview" role="listbox">
			<?php
				$teams = $db->getPotentialTeams($team_id, $user1_id, $user2_id);
				$teams = json_decode($teams);
				foreach ($teams as $team) {
					echo '<li>';
					echo '<img src="https://graph.facebook.com/' . $team->{"user1_id"} . '/picture" />';
					echo '<img src="https://graph.facebook.com/' . $team->{"user2_id"} . '/picture" />';
					echo $team->{"user1_name"} . ' and ' . $team->{"user2_name"};
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