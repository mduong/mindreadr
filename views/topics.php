<?php 
	require '../lib/MindReadrDb.php';
	require '../lib/facebook.php';
	
	$facebook = new Facebook(array(
		'appId' => '165478150170952',
		'secret' => '954447415b7f3d150c4772af1a66b4df',
		'cookie' => true,
	));
	
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
		<a href="#" class="ui-btn-left ui-btn ui-btn-icon-left ui-btn-corner-all ui-shadow ui-btn-up-a" data-rel="back" data-icon="arrow-l" data-theme="a">
			<span class="ui-btn-inner ui-btn-corner-all">
				<span class="ui-btn-text">Back</span>
				<span class="ui-icon ui-icon-arrow-l ui-icon-shadow"></span>
			</span>
		</a>
	</div><!-- /header -->

	<div data-role="content">
		<div data-role="controlgroup">
			<?php
				$topics = $db->getTopics();
				$topics = json_decode($topics);
				foreach ($topics as $topic) {
					echo '<a href="friends.php?topic=' . $topic->{"topic_id"} . '" data-role="button">' . $topic->{"topic"} . '</a>';
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