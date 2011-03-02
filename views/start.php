<?php 
	require_once '../lib/MindReadrDb.php';
	require_once '../lib/fb_config.php';
	
	$db = new MindReadrDb();
	
	session_start();
	
	$topic = $db->getTopicName($_GET["topic_id"]);
	$my_team = json_decode($db->getTeam($_GET["team_id"])); // need to check if valid team
	$opponent_team = json_decode($db->getTeam($_GET["opponent_id"]));
?>

<!DOCTYPE html> 
<html> 
	<head> 
	<title>Start</title> 
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a3/jquery.mobile-1.0a3.min.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.3.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/mobile/1.0a3/jquery.mobile-1.0a3.min.js"></script>
</head> 
<body> 

<div data-role="page">

	<div data-role="header">
		<h1>Start</h1>
		<a href="#" data-rel="back" data-icon="arrow-l">Back</a>
		<a href="/~mduong/ed196x/" data-role="button" data-icon="home" data-iconpos="notext"></a>
	</div><!-- /header -->

	<div data-role="content"
		<?php 
			echo "You and <strong>" . $my_team->{"user2_name"} . "</strong> are playing against <strong>" . $opponent_team->{"user1_name"} . "</strong> and <strong>" . $opponent_team->{"user2_name"} . "</strong>.<br />";
			echo "The topic is: <strong>" . $topic . "</strong>";
		?>
		<div data-inline="true">
			<div id="start" data-role="button" onclick="createGame(<?php echo $_GET['topic_id']; ?>, <?php echo $_GET['team_id'];_?>, <?php echo $_GET['opponent_id']; ?>);">Start</div>
		</div>
	</div><!-- /content -->

	<div data-role="footer">
		<h4>EDUC 196X</h4>
	</div><!-- /footer -->
</div><!-- /page -->

</body>
</html>