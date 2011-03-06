<?php
	require_once 'lib/MindReadrDb.php';
	require_once 'lib/fb_config.php';
	
	$db = new MindReadrDb();
	
	session_start();
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
				<a href="<?php echo $facebook->getLogoutUrl(array('next' => 'http://cgi.stanford.edu/~mduong/ed196x/actions/account/logout.php')); ?>" class="ui-btn-right">Logout</a>
			</div>
			
			<div data-role="content">
				<ul data-role="listview">
					<li>
					<?php
						if ($_SESSION['me']) {
							echo 'Welcome back ' . $_SESSION['me']['first_name'] . '!';
						} else {
							try {
								$me = $facebook->api('/me');
								if (!$db->userExists($me['id'])) {
									$db->createUser($me);
									$friends = $facebook->api('/me/friends');
									$db->addFriends($me['id'], $friends["data"]);
									echo 'Welcome to MindReadr, ' . $me['first_name'] . '!';
								} else {
									echo 'Welcome back ' . $me['first_name'] . '!';
								}
								$_SESSION['me'] = $me;
							} catch (FacebookApiException $e) {
								error_log($e);
							}
						}
					?>
					</li>
				</ul>
				<br />
				<div id="clue_box" data-role="controlgroup">
					<h3>Give a clue</h3>
					<?php
						$games = json_decode($db->getGiveClueGames($_SESSION['me']['id']));
						if (sizeof($games)) {
							foreach($games as $game) {
								$opponent = json_decode($db->getOpponent($game->{"game_id"}, $_SESSION['me']['id']));
								if ($game->{"state"} == $db->STATE_DIFFICULTY) {
									echo '<div data-role="button" onclick="$.mobile.changePage(\'views/play.php?game_id=' . $game->{"game_id"} . '\');">Give ' . $opponent->{"first_name"} . ' a clue!</div>';
								} else if ($game->{"state"} == $db->STATE_GIVE_CLUE) {
									echo '<div data-role="button" onclick="$.mobile.changePage(\'views/give_clue.php?game_id=' . $game->{"game_id"}  . '&difficulty=' . $game->{"difficulty"} . '&turn=' . $game->{"turn"} . '\');">Give ' . $opponent->{"first_name"} . ' a clue!</div>';
								}
							}
						} else {
							echo 'No games where you need to provide clues!';
						}
					?>
				</div>
				<div id="guess_box" data-role="controlgroup">
					<h3>Guess the answer</h3>
					<?php
						$games = json_decode($db->getGuessAnswerGames($_SESSION['me']['id']));
						if (sizeof($games)) {
							foreach($games as $game) {
								$opponent = json_decode($db->getOpponent($game->{"game_id"}, $_SESSION['me']['id']));
								if ($game->{"state"} == $db->STATE_GUESS) {
									echo '<div data-role="button" onclick="$.mobile.changePage(\'views/guess.php?game_id=' . $game->{"game_id"} . '&turn=' . $game->{"turn"} . '\');">Guess ' . $opponent->{"first_name"} . '\'s answer!</div>';
								}
							}
						} else {
							echo 'No games that need your guess!';
						}
					?>
				</div>
				<div id="clue_box" data-role="controlgroup">
					<h3>Pending games</h3>
					<?php
						$games = json_decode($db->getPendingGames($_SESSION['me']['id']));
						if (sizeof($games)) {
							foreach($games as $game) {
								$opponent = json_decode($db->getOpponent($game->{"game_id"}, $_SESSION['me']['id']));
								if ($game->{"state"} == $db->STATE_DONE_CLUE) {
									echo '<div data-role="button" onclick="$.mobile.changePage(\'views/given_wait.php?game_id=' . $game->{"game_id"} . '\');">Wait for ' . $opponent->{"first_name"} . ' to guess!</div>';
								} else if ($game->{"state"} == $db->STATE_WAIT_CLUE) {
									echo '<div data-role="button">Wait for ' . $opponent->{"first_name"} . '\'s clue!</div>';
								}
							}
						} else {
							echo 'No pending games!';
						}
					?>
				</div>
				<div data-role="controlgroup">
					<h3>Menu</h3>
					<a href="views/topics.php?play=friends" data-role="button">Play with Friends</a>
					<a href="views/friends.php" data-role="button">Friends</a>
					<a href="views/topics.php" data-role="button">Topics</a>
				</div>
			</div>
			
			<div data-role="footer">
				<h4>EDUC 196X</h4>
			</div>
		</div>
	</body>
</html>