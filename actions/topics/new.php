<?php
	require '../../lib/MindReadrDb.php';
	
	$db = new MindReadrDb();
	
    $topic = $_POST["topic"];
	
	$db->addTopic($topic);
	
	echo $db->getTopicsSelect();
?>