<?php
    include("../../lib/MindReadrDb.php");
	
	$db = new MindReadrDb();
	
	$user1_id = $_POST["user1_id"];
	$user2_id = $_POST["user2_id"];
	
	if ($team_id = $db->teamExists($user1_id, $user2_id)) {
		echo $team_id;
	} else {
		$db->createTeam($user1_id, $user2_id);
		echo $db->teamExists($user1_id, $user2_id);
	}
?>