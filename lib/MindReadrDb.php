<?php

include_once("config.php");

class MindReadrDb {
	
	public $db_handle;
	
	function MindReadrDb() {
    	global $SERVER_SETTINGS;
    	if (!$this->db_handle = new SQLiteDatabase($SERVER_SETTINGS["DATABASE_PATH"], 0666, $err)) {
      		echo "Error connecting to database";
      		die($err);
		}
	}
	
	function createUser($user_obj) {
		$user_id = sqlite_escape_string($user_obj['id']);
		$first_name = sqlite_escape_string($user_obj['first_name']);
		$last_name = sqlite_escape_string($user_obj['last_name']);
		$email = sqlite_escape_string($user_obj['email']);
		
		$user_sql = "INSERT INTO users(user_id, first_name, last_name, email) VALUES(%d, '%s', '%s', '%s')";
		$user_sql = sprintf($user_sql, $user_id, $first_name, $last_name, $email);
		return $this->db_handle->queryExec($user_sql);
	}
	
	function userExists($user_id) {
		$user_sql = "SELECT * FROM users WHERE user_id='" . sqlite_escape_string($user_id) . "'"; 
		if ($result = $this->db_handle->query($user_sql)) {
			return $result->numRows() > 0;
		}
		return false;
	}
	
	function createAnswer($answer, $answer_type, $difficulty, $topic, $media, $media_type) {
		$answer = sqlite_escape_string($answer);
		$answer_type = sqlite_escape_string($answer_type);
		$difficulty = sqlite_escape_string($difficulty);
		$topic = sqlite_escape_string($topic);
		$media = sqlite_escape_string($media);
		$media_type = sqlite_escape_string($media_type);
		$media_query = "INSERT INTO media(media, type) VALUES ('%s', '%s')";
		$media_query = sprintf($media_query, $media, $media_type);
		if ($this->db_handle->queryExec($media_query)) {
			$last_insert_rowid = sqlite_last_insert_rowid($this->db_handle);
			$answer_query = "INSERT INTO answers(answer, answer_type, difficulty, topic, media_id) VALUES ('%s', '%s', '%d', '%d', '%d')";
			$answer_query = sprintf($answer_query, $answer, $answer_type, $difficulty, $topic, $last_insert_rowid);
			return $this->db_handle->queryExec($answer_query);
		}
		return false;
	}
	
	function getUserGames($user_id, $who) {
		
	}
	
	function addFriends($user_id, $friends) {
		$user_id = sqlite_escape_string($user_id);
		$friends_query = "";
		foreach ($friends as $friend) {
			if ($this->userExists($friend["id"])) {
				$friend_id = sqlite_escape_string($friend["id"]);
				$friends_query .= "INSERT INTO friends(friend1_id, friend2_id) VALUES ($user_id, $friend_id); ";
				$friends_query .= "INSERT INTO friends(friend1_id, friend2_id) VALUES ($friend_id, $user_id); ";
			}
		}
		if ($friends_query != "") {
			return $this->db_handle->queryExec($friends_query);
		}
		return false;
	}
	
	function getFriends($user_id) {
		$user_id = sqlite_escape_string($user_id);
		$friends_query = "SELECT friend2_id FROM friends WHERE friend1_id='" . $user_id . "'";
		$result =  $this->db_handle->query($friends_query);
		$friends = array();
		while ($row = $result->fetch()) {
			$friends[] = $row;
		}
		return json_encode($friends);
	}
	
	function createTeam($user1_id, $user2_id) {
		$user1_id = sqlite_escape_string($user1_id);
		$user2_id = sqlite_escape_string($user2_id);
		$team_query = "INSERT INTO teams(user1_id, user2_id) VALUES (%d, %d); ";
		$team_query .= "INSERT INTO teams(user1_id, user2_id) VALUES (%d, %d)";
		$team_query = sprintf($team_query, $user1_id, $user2_id, $user2_id, $user1_id);
		return $this->db_handle->queryExec($team_query);
	}
	
	function getUserTeams($user_id) {
		$user_id = sqlite_escape_string($user_id);
		$team_query = "SELECT team_id, user2_id FROM teams WHERE user1_id=" . $user_id;
		$result = $this->db_handle->query($team_query);
		$teams = array();
		while ($row = $result->fetch()) {
			$teams[] = $row;
		}
		return json_encode($teams);
	}
	
	function addTopic($topic) {
		$topic = sqlite_escape_string($topic);
		$topic_query = "INSERT INTO topics(topic) VALUES ('%s')";
		$topic_query = sprintf($topic_query, $topic);
		return $this->db_handle->queryExec($topic_query);
	}
	
	function getTopicsSelect() {
		$result = $this->db_handle->query("SELECT * FROM topics");
		$topics_select = "";
		while ($row = $result->fetch()) {
			$topics_select .= '<option value="' . $row["topic_id"] . '">' . $row["topic"] . '</option>';
		}
		return $topics_select;
	}
}

?>
