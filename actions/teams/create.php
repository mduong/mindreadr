<?php
	require_once '../../lib/MindReadrDb.php';
	
	$db = new MindReadrDb();

	$user1_id = $_POST["user1_id"];
	$user2_id = $_POST["user2_id"];

	if (!($team_id = $db->teamExists($user1_id, $user2_id))) {
		$db->createTeam($user1_id, $user2_id);
		$team_id = $db->teamExists($user1_id, $user2_id);
	}
	
	echo $team_id;
?>