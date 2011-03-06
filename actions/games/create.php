<?php
	require_once '../../lib/MindReadrDb.php';
	
	$db = new MindReadrDb();

	$topic_id = $_POST["topic_id"];
	$user1_id = $_POST["user1_id"];
	$user2_id = $_POST["user2_id"];
	//$team1_id = $_POST["team1_id"];
	//$team2_id = $_POST["team2_id"];
	
	echo $db->createGame($user1_id, $user2_id, $topic_id);
?>
