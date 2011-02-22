<?php
	require_once 'facebook.php';
	
	$app_id = "165478150170952";
	$app_secret = "954447415b7f3d150c4772af1a66b4df";
	$facebook = new Facebook(array(
		'appId' => $app_id,
		'secret' => $app_secret,
		'cookie' => true
	));
	
	if (is_null($facebook->getUser())) {
	 	header("Location:{$facebook->getLoginUrl(array('req_perms' => 'email'))}");
	 	exit;
	}
?>