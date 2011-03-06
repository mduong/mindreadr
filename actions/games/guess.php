<?php
    require_once '../../lib/MindReadrDb.php';
	
	$db = new MindReadrDb();

	$game_id = $_POST["game_id"];
	$clue_id = $_POST["clue_id"];
	$user_id = $_POST["user_id"];
	$points = $_POST["points"];
	$guess = $_POST["guess"];
	
	echo $db->validateGuess($game_id, $user_id, $clue_id, $guess, $points);
?>