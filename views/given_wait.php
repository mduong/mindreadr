<?php
    require_once '../lib/MindReadrDb.php';
	require_once '../lib/fb_config.php';
	
	$db = new MindReadrDb();
	
	session_start();

	$game_id = $_GET["game_id"];

	if ($game = $db->getGame($game_id)) {
		$game = json_decode($game);
	}
	
	$topic = $db->getTopicName($game->{"topic_id"});
	
	$opponent = json_decode($db->getOpponent($game_id, $_SESSION["me"]["id"]));
?>

<div data-role="page">

	<div data-role="header">
		<h1>MindReadr</h1>
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
		<h3><?php echo $opponent->{"first_name"}; ?>'s turn</h3>
		Your clue has been sent to <?php echo $opponent->{"first_name"}; ?>. Please wait for <?php echo $opponent->{"first_name"}; ?> to guess.
	</div><!-- /content -->
	
	<div data-role="footer">
		<h4>EDUC 196X</h4>
	</div>

</div><!-- /page -->