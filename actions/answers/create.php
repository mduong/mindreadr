<?php
	include("../../lib/MindReadrDb.php");
	
	$answer = $_POST["answer"];
	$answer_type = $_POST["answer_type"];
	$topic = $_POST["topic"];
	
	$media0_type = $_POST["media0_type"];
	$media1_type = $_POST["media1_type"];
	$media2_type = $_POST["media2_type"];

	if ($media0_type == "text") {
		$media0 = $_POST["media0"];
	} else if ($media0_type == "image") {
		$media0 = "../../upload/" . $_FILES["media"]["name"][0];
		$media0_tmp = $_FILES["media"]["tmp_name"][0];
		if (!file_exists($media0)) {
			move_uploaded_file($media0_tmp, $media0);
		}
	}
	
	if ($media1_type == "text") {
		$media1 = $_POST["media1"];
	} else if ($media1_type == "image") {
		$media1 = "../../upload/";
		if ($media0_type == "image") {
			$media1 .= $_FILES["media"]["name"][1];
			$media1_tmp = $_FILES["media"]["name"][1];
			
		} else {
			$media1 .= $_FILES["media"]["name"][0];
			$media1_tmp = $_FILES["media"]["tmp_name"][0];
		}
		if (!file_exists($media1)) {
			move_uploaded_file($media1_tmp, $media1);
		}
	}
	
	if ($media2_type == "text") {
		$media2 = $_POST["media2"];
	} else if ($media2_type == "image") {
		$media2 = "../../upload/";
		if ($media0_type == "image" && $media1_type == "image") {
			$media2 .= $_FILES["media"]["name"][2];
			$media2_tmp = $_FILES["media"]["name"][2];
		} else if ($media0_type == "image" || $media1_type == "image") {
			$media2 .= $_FILES["media"]["name"][1];
			$media2_tmp = $_FILES["media"]["tmp_name"][1];
		} else {
			$media2 .= $_FILES["media"]["name"][0];
			$media2_tmp = $_FILES["media"]["tmp_name"][0];
		}
		if (!file_exists($media2)) {
			move_uploaded_file($media2_tmp, $media2);
		}
	}
	
	$db = new MindReadrDb();
	$db->createAnswer($answer, $answer_type, 0, $topic, $media0, $media0_type);
	$db->createAnswer($answer, $answer_type, 1, $topic, $media1, $media1_type);
	$db->createAnswer($answer, $answer_type, 2, $topic, $media2, $media2_type);
?>
