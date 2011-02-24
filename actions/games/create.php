<?php
	require_once '../../lib/MindReadrDb.php';
	
	$db = new MindReadrDb();

	$topic_id = $_POST["topic_id"];
	$team1_id = $_POST["team1_id"];
	$team2_id = $_POST["team2_id"];
	
	echo $db->createGame($team1_id, $team2_id, $topic_id);
?>
