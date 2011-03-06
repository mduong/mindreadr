<?php
    require_once '../lib/MindReadrDb.php';
	require_once '../lib/fb_config.php';
	
	$db = new MindReadrDb();
	
	session_start();

	$game_id = $_GET["game_id"];
	$answer_id = $_GET["answer_id"];
	$difficulty = $_GET["difficulty"];
	$turn = $_GET["turn"];
	$opponent = json_decode($db->getOpponent($game_id, $_SESSION["me"]["id"]));

	if ($game = $db->getGame($game_id)) {
		$game = json_decode($game);
	}
	
	if (!$answer_id) {
		$answer = json_decode($db->getAnswerNoId($game_id, $difficulty, $turn));
	} else {	
		$answer = $db->getAnswer($answer_id, $difficulty);
		if ($answer) {
			$answer = json_decode($answer);
		}
	}
?>

<div data-role="page">

	<div data-role="header">
		<h1>MindReadr</h1>
		<a href="#" data-rel="back" data-icon="arrow-l">Back</a>
		<a href="http://cgi.stanford.edu/~mduong/ed196x/" data-role="button" data-icon="home" data-iconpos="notext" rel="external" class="ui-btn-right"></a>
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
		<h3>Answer: </h3>
		<?php
			if ($answer->{"type"} == "text") {
				echo '<p class="answer_text">' . $answer->{"media"} . '</p>';
			} else if ($answer->{"type"} == "image") {
				echo '<div class="clue_img">';
				echo '<img src="' . substr($answer->{"media"}, 3) . '" class="clue" />';
				echo '</div>';
			}
		?>
		<div data-role="button" onclick="revealAnswer(<?php echo $answer->{'answer_id'}; ?>);" id="reveal_btn">Reveal answer</div>
		<div id="answer_container"></div>
		<form action="../actions/games/submit_clue.php" method="get">
			<div data-role="fieldcontain">
			    <label for="clue">Your clue:</label>
			    <input type="text" name="clue" id="clue" value=""  />
			</div>
			<input type="hidden" name="game_id" value="<?php echo $game_id; ?>" />
			<input type="hidden" name="answer_id" value="<?php echo $answer_id; ?>" />
			<input type="hidden" name="user_id" value="<?php echo $_SESSION["me"]["id"]; ?>" />
			<input type="hidden" name="points" value="10" />
			<input type="submit" value="Submit" />
		</form>
	</div><!-- /content -->

</div><!-- /page -->