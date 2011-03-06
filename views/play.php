<?php
    require_once '../lib/MindReadrDb.php';
	require_once '../lib/fb_config.php';
	
	$db = new MindReadrDb();
	
	session_start();
	
	$game_id = $_GET["game_id"];
	$user_id = $_SESSION["me"]["id"];
	$opponent = json_decode($db->getOpponent($game_id, $user_id));
	
	if ($game = $db->getGame($game_id)) {
		$game = json_decode($game);
	}
	
	$topic = $db->getTopicName($game->{"topic_id"});
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
				<img src="https://graph.facebook.com/<?php echo $user_id; ?>/picture" />
				<img src="https://graph.facebook.com/<?php echo $opponent->{"user_id"}; ?>/picture" />
			</div>
		</div><!-- /grid-a -->
		<h3>Select a difficulty: </h3>
		<button data-type="button" onclick="chooseDifficulty(<?php echo $game_id . ", " . $user_id; ?>, 1);">Easy</button>
		<button data-type="button" onclick="chooseDifficulty(<?php echo $game_id . ", " . $user_id; ?>, 2);">Medium</button>
		<button data-type="button" onclick="chooseDifficulty(<?php echo $game_id . ", " . $user_id; ?>, 3);">Hard</button>
	</div><!-- /content -->

	<div data-role="footer">
		<h4>EDUC 196X</h4>
	</div><!-- /footer -->
</div><!-- /page -->

</body>
</html>