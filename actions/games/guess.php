<?php
    require_once '../../lib/MindReadrDb.php';
	
	$db = new MindReadrDb();

	$game_id = $_GET["game_id"];
	$team_id = $_GET["team_id"];
	$clue_id = $_GET["clue_id"];
	$user_id = $_GET["user_id"];
	$points = $_GET["points"];
	$guess = $_GET["guess"];
	
	echo $db->validateGuess($team_id, $user_id, $clue_id, $guess, $points);
?>