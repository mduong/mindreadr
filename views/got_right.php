<?php
    require_once '../lib/MindReadrDb.php';
	require_once '../lib/fb_config.php';
	
	$db = new MindReadrDb();
	
	session_start();

	$game_id = $_GET["game_id"];
	$team_id = $_GET["team_id"];
	$answer_id = $_GET["answer_id"];
	
	$teammate = json_decode($db->getTeammate($team_id, $_SESSION["me"]["id"]));

	if ($team = $db->getTeam($team_id)) {
		$team = json_decode($team);
	}
	
	$answer = $db->getAnswer($answer_id, $team->{"difficulty"});
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
		<h4>You got it! This is what <?php echo $teammate->{"first_name"}; ?> worked with:</h4>
		<?php
			if ($answer->{"type"} == "text") {
				echo '<p class="answer_text">' . $answer->{"media"} . '</p>';
			} else if ($answer->{"type"} == "image") {
				echo '<img src="' . substr($answer->{"media"}, 3) . '" class="answer_img" />';
			}
		?>
	</div><!-- /content -->

</div><!-- /page -->