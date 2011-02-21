<?php

include_once("config.php");

class MindReadrDb {
	
	public $db_handle;
	
	function MindReadrDb() {
    	global $SERVER_SETTINGS;
    	if (!$this->db_handle = sqlite_open($SERVER_SETTINGS["DATABASE_PATH"], 0666, $err)) {
      		echo "Error connecting to database";
      		die($err);
		}
	}
	
	function createUser($user_obj) {
		$user_id = sqlite_escape_string($user_obj['id']);
		$first_name = sqlite_escape_string($user_obj['first_name']);
		$last_name = sqlite_escape_string($user_obj['last_name']);
		$email = sqlite_escape_string($user_obj['email']);
		
		$user_sql = "INSERT INTO users(user_id, first_name, last_name, email) VALUES('%d', '%s', '%s', '%s')";
		$user_sql = sprintf($user_sql, $user_id, $first_name, $last_name, $email);
		return sqlite_exec($this->db_handle, $user_sql);
	}
	
	function userExists($user_id) {
		$user_sql = "SELECT * FROM users WHERE user_id='" . sqlite_escape_string($user_id) . "'"; 
		return sqlite_query($this->db_handle, $user_sql);
	}
	
	function createAnswer($answer, $answer_type, $difficulty, $media, $media_type) {
		$answer = sqlite_escape_string($answer);
		$answer_type = sqlite_escape_string($answer_type);
		$difficulty = sqlite_escape_string($difficulty);
		$media = sqlite_escape_string($media);
		$media_type = sqlite_escape_string($media_type);
		$media_query = "INSERT INTO media(media, type) VALUES ('%s', '%s')";
		$media_query = sprintf($media_query, $media, $media_type);
		if (sqlite_exec($this->db_handle, $media_query)) {
			$last_insert_rowid = sqlite_last_insert_rowid($this->db_handle);
			$answer_query = "INSERT INTO answers(answer, answer_type, difficulty, media_id) VALUES ('%s', '%s', '%d', '%d')";
			$answer_query = sprintf($answer_query, $answer, $answer_type, $difficulty, $last_insert_rowid);
			return sqlite_exec($this->db_handle, $answer_query);
		}
		return false;
	}
}

?>
