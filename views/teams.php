<?php 
	require_once '../lib/MindReadrDb.php';
	require_once '../lib/fb_config.php';
	
	$db = new MindReadrDb();
	
	session_start();
	
	$user1_id = $_GET["user1_id"];
	$user2_id = $_GET["user2_id"];
	
	$team_id = $_GET["team_id"];
?>

<!DOCTYPE html> 
<html> 
	<head> 
	<title>Select opponents</title> 
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a3/jquery.mobile-1.0a3.min.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.3.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/mobile/1.0a3/jquery.mobile-1.0a3.min.js"></script>
</head> 
<body> 

<div data-role="page">

	<div data-role="header">
		<h1>Select opponents</h1>
		<a href="#" data-rel="back" data-icon="arrow-l">Back</a>
		<a href="http://cgi.stanford.edu/~mduong/ed196x/" data-role="button" data-icon="home" data-iconpos="notext" rel="external" class="ui-btn-right"></a>
	</div><!-- /header -->

	<div data-role="content">	
		<ul data-role="listview" role="listbox">
			<?php
				$teams = $db->getPotentialTeams($team_id, $user1_id, $user2_id);
				$teams = json_decode($teams);
				if (empty($teams)) {
					echo '<li>There are no opponents available!</li>';
				} else {
					foreach ($teams as $team) {
						echo '<li>';
						echo '<img src="https://graph.facebook.com/' . $team->{"user1_id"} . '/picture" />';
						echo '<img src="https://graph.facebook.com/' . $team->{"user2_id"} . '/picture" />';
						echo '<a href="start.php?topic_id=' . $_GET["topic_id"] . '&team_id=' . $team_id . '&opponent_id=' . $team->{"team_id"} . '">';
						echo $team->{"user1_name"} . ' and ' . $team->{"user2_name"} . '</a>';
						echo '</li>';
					}
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