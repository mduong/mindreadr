<?php
    require_once '../lib/MindReadrDb.php';
	require_once '../lib/fb_config.php';
	
	$db = new MindReadrDb();
	
	session_start();
	
	$game_id = $_GET["game_id"];
	$team_id = $_GET["team_id"];
	$user_id = $_SESSION["me"]["id"];
	$teammate = json_decode($db->getTeammate($team_id, $user_id));
	
	if ($team = $db->getTeam($team_id)) {
		$team = json_decode($team);
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>MindReadr</title>
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a3/jquery.mobile-1.0a3.min.css" />
		<link rel="stylesheet" href="http://cgi.stanford.edu/~mduong/ed196x/lib/css/mindreadr.css" />
		<script src="http://code.jquery.com/jquery-1.5.min.js"></script>
		<script src="http://cgi.stanford.edu/~mduong/ed196x/lib/js/mindreadr.js"></script>
		<script src="http://code.jquery.com/mobile/1.0a3/jquery.mobile-1.0a3.min.js"></script>
	</head>
	<body>

<div data-role="page">

	<div data-role="header">
		<h1>MindReadr</h1>
		<a href="#" data-rel="back" data-icon="arrow-l">Back</a>
		<a href="/~mduong/ed196x/" data-role="button" data-icon="home" data-iconpos="notext"></a>
	</div><!-- /header -->

	<div data-role="content">
		<div class="ui-grid-a">
			<div class="ui-block-a">
				Turn: <strong><?php echo $team->{"turn"}; ?></strong><br />
				Score: <strong><?php echo $team->{"score"}; ?></strong>
			</div>
			<div class="ui-block-b">
				<img src="https://graph.facebook.com/<?php echo $user_id; ?>/picture" />
				<img src="https://graph.facebook.com/<?php echo $teammate->{"user_id"}; ?>/picture" />
			</div>
		</div><!-- /grid-a -->
		<h3>Select a difficulty: </h3>
		<button data-type="button" onclick="chooseDifficulty(<?php echo $game_id . ", " . $team_id . ", " . $user_id; ?>, 1);">Easy</button>
		<button data-type="button" onclick="chooseDifficulty(<?php echo $game_id . ", " . $team_id . ", " . $user_id; ?>, 2);">Medium</button>
		<button data-type="button" onclick="chooseDifficulty(<?php echo $game_id . ", " . $team_id . ", " . $user_id; ?>, 3);">Hard</button>
	</div><!-- /content -->

</div><!-- /page -->

</body>
</html>