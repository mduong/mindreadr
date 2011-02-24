<?php
    require_once '../lib/MindReadrDb.php';
	require_once '../lib/fb_config.php';
	
	$db = new MindReadrDb();
	
	session_start();

	$game_id = $_GET["game_id"];
	$team_id = $_GET["team_id"];
	$clue_id = $_GET["clue_id"];
	$turn = $_GET["turn"];
	$teammate = json_decode($db->getTeammate($team_id, $_SESSION["me"]["id"]));

	if ($team = $db->getTeam($team_id)) {
		$team = json_decode($team);
	}
	
	if (!$clue_id) {
		$clue = json_decode($db->getClueNoId($team_id, $turn));
	} else {
		$clue = $db->getClue($clue_id);
		if ($clue) {
			$clue = json_decode($answer);
		}
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
				<img src="https://graph.facebook.com/<?php echo $_SESSION["me"]["id"]; ?>/picture" />
				<img src="https://graph.facebook.com/<?php echo $teammate->{"user_id"}; ?>/picture" />
			</div>
		</div><!-- /grid-a -->
		<h4>Clue:</h4>
		<?php
			echo '<p class="clue_text">' . $clue->{"clue"} . '</p>';
		?>
		<form action="../actions/games/submit_guess.php" method="get">
			<div data-role="fieldcontain">
			    <label for="clue">Your guess:</label>
			    <input type="text" name="guess" id="guess" value=""  />
			</div>
			<input type="hidden" name="game_id" value="<?php echo $game_id; ?>" />
			<input type="hidden" name="team_id" value="<?php echo $team_id; ?>" />
			<input type="hidden" name="clue_id" value="<?php echo $clue->{"clue_id"}; ?>" />
			<input type="hidden" name="user_id" value="<?php echo $_SESSION["me"]["id"]; ?>" />
			<input type="hidden" name="points" value="10" />
			<div data-role="button" onclick="">Submit</div>
		</form>
	</div><!-- /content -->

</div><!-- /page -->