<?php

require_once("MindReadrDb.php");

class Answer {
	
	protected $id, $topic, $difficulty, $answer, $media, $db;
	
	public function Answer() {
		$this->db = new MindReadrDb();
	}
	
	public function Answer($answer_data) {
		
	}
	
	public function Answer($id) {
		
	}
	
	public function getId() {
		return $id;
	}
	
	public function getTopic() {
		return $topic;
	}
	
	public function getDifficulty() {
		return $difficulty;
	}
	
	public function getAnswer() {
		return $answer;
	}
	
	public function getMedia() {
		return $media;
	}
	
	public set
}

?>
