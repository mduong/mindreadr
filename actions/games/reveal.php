<?php
    require_once '../../lib/MindReadrDb.php';
	
	$db = new MindReadrDb();

	$answer_id = $_POST["answer_id"];
	
	echo $db->getAnswerReveal($answer_id);
?>