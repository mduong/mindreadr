<?php
    require_once '../lib/MindReadrDb.php';
	require_once '../lib/fb_config.php';
	
	$db = new MindReadrDb();
	
	session_start();

	$game_id = $_GET["game_id"];
	$team_id = $_GET["team_id"];

	if ($team = $db->getTeam($team_id)) {
		$team = json_decode($team);
	}
	
	$teammate = json_decode($db->getTeammate($team_id, $_SESSION["me"]["id"]));
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
				<img src="https://graph.facebook.com/<?php echo $_SESSION["me"]["id"]; ?>/picture" />
				<img src="https://graph.facebook.com/<?php echo $teammate->{"user_id"}; ?>/picture" />
			</div>
		</div><!-- /grid-a -->
		<h3><?php echo $teammate->{"first_name"}; ?>'s turn</h3>
		Your clue has been sent to <?php echo $teammate->{"first_name"}; ?>. Please wait for them to guess.
	</div><!-- /content -->
	
	<div data-role="footer">
		<h4>EDUC 196X</h4>
	</div>

</div><!-- /page -->