<?php 
	require_once '../lib/MindReadrDb.php';
	require_once '../lib/fb_config.php';
	
	$db = new MindReadrDb();
	
	session_start();
?>

<!DOCTYPE html> 
<html> 
	<head> 
	<title>Topics</title> 
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a3/jquery.mobile-1.0a3.min.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.3.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/mobile/1.0a3/jquery.mobile-1.0a3.min.js"></script>
</head> 
<body> 

<div data-role="page">

	<div data-role="header">
		<h1>Topics</h1>
		<a href="#" data-rel="back" data-icon="arrow-l">Back</a>
		<a href="http://cgi.stanford.edu/~mduong/ed196x/" data-role="button" data-icon="home" data-iconpos="notext" rel="external" class="ui-btn-right"></a>
	</div><!-- /header -->

	<div data-role="content">
		<div data-role="controlgroup">
			<?php
				$topics = $db->getTopics();
				$topics = json_decode($topics);
				foreach ($topics as $topic) {
					if ($_GET["play"] == "friends") {
						echo '<a href="friends.php?topic_id=' . $topic->{"topic_id"} . '" data-role="button">' . $topic->{"topic"} . '</a>';
					} else {
						echo '<a href="play.php?topic_id=' . $topic->{"topic_id"} . '" data-role="button">' . $topic->{"topic"} . '</a>';
					}
				}
			?>
		</div>
	</div><!-- /content -->

	<div data-role="footer">
		<h4>EDUC 196X</h4>
	</div><!-- /footer -->
</div><!-- /page -->

</body>
</html>