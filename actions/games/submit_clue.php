<?php
    require_once '../../lib/MindReadrDb.php';
	
	$db = new MindReadrDb();

	$game_id = $_GET["game_id"];
	$team_id = $_GET["team_id"];
	$answer_id = $_GET["answer_id"];
	$user_id = $_GET["user_id"];
	$clue = $_GET["clue"];
	$points = $_GET["points"];
	
	$db->recordClue($game_id, $team_id, $user_id, $answer_id, $clue, $points);
	
	header("Location: http://cgi.stanford.edu/~mduong/ed196x/views/given_wait.php?game_id=" . $game_id . "&team_id=" . $team_id);
?>