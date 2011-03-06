<?php
    require_once '../lib/MindReadrDb.php';
	require_once '../lib/fb_config.php';
	
	$db = new MindReadrDb();
	
	session_start();

	$game_id = $_GET["game_id"];
	$clue_id = $_GET["clue_id"];
	$turn = $_GET["turn"];
	$opponent = json_decode($db->getOpponent($game_id, $_SESSION["me"]["id"]));

	if ($game = $db->getGame($game_id)) {
		$game = json_decode($game);
	}
	
	if (!$clue_id) {
		$clue = json_decode($db->getClueNoId($game_id, $turn));
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
		<a href="#" data-rel="back" data-icon="arrow-l">Back</a>
		<a href="/~mduong/ed196x/" data-role="button" data-icon="home" data-iconpos="notext"></a>
	</div><!-- /header -->

	<div data-role="content">
		<div class="ui-grid-a">
			<div class="ui-block-a">
				Turn: <strong><?php echo $game->{"turn"}; ?></strong><br />
				Your Score: <strong>
					<?php 
						if ($_SESSION["me"]["id"] == $game->{"user1_id"}) {
							echo $game->{"score1"};
						} else if ($_SESSION["me"]["id"] == $game->{"user2_id"}) {
							echo $game->{"score2"};
						}
					?>
				</strong><br />
				<?php echo $opponent->{"first_name"}; ?>'s Score: <strong>
					<?php 
						if ($_SESSION["me"]["id"] == $game->{"user1_id"}) {
							echo $game->{"score2"};
						} else if ($_SESSION["me"]["id"] == $game->{"user2_id"}) {
							echo $game->{"score1"};
						}
					?>
				</strong>
			</div>
			<div class="ui-block-b">
				<img src="https://graph.facebook.com/<?php echo $_SESSION["me"]["id"]; ?>/picture" />
				<img src="https://graph.facebook.com/<?php echo $opponent->{"user_id"}; ?>/picture" />
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
			<div data-role="button" onclick="validateGuess(<?php echo $game_id . ',' . $clue->{'clue_id'} . ',' . $_SESSION['me']['id']; ?>,10);">Submit</div>
		</form>
	</div><!-- /content -->

</div><!-- /page -->