<?php
    require_once '../lib/MindReadrDb.php';
	require_once '../lib/fb_config.php';
	
	$db = new MindReadrDb();
	
	session_start();

	$game_id = $_GET["game_id"];
	$answer_id = $_GET["answer_id"];
	
	$opponent = json_decode($db->getOpponent($game_id, $_SESSION["me"]["id"]));

	if ($game = $db->getGame($game_id)) {
		$game = json_decode($game);
	}
	
	$topic = $db->getTopicName($game->{"topic_id"});
	
	$answer = json_decode($db->getAnswer($answer_id, $game->{"difficulty"}));
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
		<h4>You got it! This is what <?php echo $opponent->{"first_name"}; ?> worked with:</h4>
		<?php
			if ($answer->{"type"} == "text") {
				echo '<p class="answer_text">' . $answer->{"media"} . '</p>';
			} else if ($answer->{"type"} == "image") {
				echo '<div class="clue_img">';
				echo '<img src="http://cgi.stanford.edu/~mduong/ed196x/' . $answer->{"media"} . '" class="clue" />';
				echo '</div>';
			}
		?>
		<p>Learn more about <a href="<?php echo $answer->{'learn_more'}; ?>" target="_blank"><?php echo $answer->{'answer'}; ?> </a>at Wikipedia.</p>
		<div data-role="button" onclick="continueGame(<?php echo $game_id; ?>);">Continue</div>
	</div><!-- /content -->

	<div data-role="footer">
		<h4>EDUC 196X</h4>
	</div><!-- /footer -->
</div><!-- /page -->