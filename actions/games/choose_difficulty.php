<?php
    require_once '../../lib/MindReadrDb.php';
	
	$db = new MindReadrDb();

	$game_id = $_POST["game_id"];
	$team_id = $_POST["team_id"];
	$user_id = $_POST["user_id"];
	$difficulty = $_POST["difficulty"];
	
	echo $db->setDifficulty($game_id, $team_id, $user_id, $difficulty);	
?>