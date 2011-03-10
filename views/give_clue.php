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
	
	$points = 0;
	switch ($difficulty) {
		case 1:
			$points = 2000;
			break;
		case 2:
			$points = 2500;
			break;
		case 3:
			$points = 3000;
			break;
	}

	if ($game = $db->getGame($game_id)) {
		$game = json_decode($game);
	}
	
	$topic = $db->getTopicName($game->{"topic_id"});
	
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
				</strong><br />
				Topic: <strong><?php echo $topic; ?></strong>
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
				echo '<img src="http://cgi.stanford.edu/~mduong/ed196x/' . $answer->{"media"} . '" class="clue" />';
				echo '</div>';
			}
		?>
		<div data-role="button" onclick="revealAnswer(<?php echo $answer->{'answer_id'} . "," . $difficulty; ?>);" id="reveal_btn">Reveal answer 
		<?php
			if ($difficulty == 1) echo "(-1000 pts)";
			else if ($difficulty == 2) echo "(-1500 pts)";
			else if ($difficulty == 3) echo "(-2000 pts)";
		?>
		</div>
		<div id="answer_container"></div>
		<form action="http://cgi.stanford.edu/~mduong/ed196x/actions/games/submit_clue.php" method="get">
			<div data-role="fieldcontain">
			    <label for="clue">Your clue:</label>
			    <input type="text" name="clue" id="clue" value=""  />
				Points: <strong><span id="points"><?php echo $points; ?></span></strong>
			</div>
			<input type="hidden" name="game_id" value="<?php echo $game_id; ?>" />
			<input type="hidden" name="answer_id" value="<?php echo $answer->{'answer_id'}; ?>" />
			<input type="hidden" name="user_id" value="<?php echo $_SESSION["me"]["id"]; ?>" />
			<input type="hidden" name="points" value="<?php echo $points; ?>" />
			<input type="submit" value="Submit" />
		</form>
	</div><!-- /content -->

	<div data-role="footer">
		<h4>EDUC 196X</h4>
	</div><!-- /footer -->
</div><!-- /page -->