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

<div data-role="page">

	<div data-role="header">
		<h1>MindReadr</h1>
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