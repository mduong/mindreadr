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
	
/**
 * User
 */
	
	function createUser($user_obj) {
		$user_id = sqlite_escape_string($user_obj['id']);
		$first_name = sqlite_escape_string($user_obj['first_name']);
		$last_name = sqlite_escape_string($user_obj['last_name']);
		$email = sqlite_escape_string($user_obj['email']);
		
		$user_sql = "INSERT INTO users(user_id, first_name, last_name, email) VALUES('%d', '%s', '%s', '%s')";
		$user_sql = sprintf($user_sql, $user_id, $first_name, $last_name, $email);
		return $this->db_handle->queryExec($user_sql);
	}
	
	function getUser($user_id) {
		$user_sql = "SELECT * FROM users WHERE user_id='" . sqlite_escape_string($user_id) . "'"; 
		$user;
		if ($result = $this->db_handle->query($user_sql)) {
			$user = $result->fetch();
		}
		return json_encode($user);
	}
	
	function userExists($user_id) {
		$user_sql = "SELECT * FROM users WHERE user_id='" . sqlite_escape_string($user_id) . "'"; 
		if ($result = $this->db_handle->query($user_sql)) {
			return $result->numRows() > 0;
		}
		return false;
	}
	
/**
 * Answer
 */
	
	function createAnswer($answer, $answer_type, $topic_id, $media0, $media0_type, $media1, $media1_type, $media2, $media2_type, $learn_more) {
		$answer = sqlite_escape_string($answer);
		$answer_type = sqlite_escape_string($answer_type);
		$difficulty = sqlite_escape_string($difficulty);
		$topic = sqlite_escape_string($topic_id);
		$media = sqlite_escape_string($media0);
		$media_type = sqlite_escape_string($media0_type);		
		$media = sqlite_escape_string($media1);
		$media_type = sqlite_escape_string($media1_type);
		$media = sqlite_escape_string($media2);
		$media_type = sqlite_escape_string($media3_type);
		$learn_more = sqlite_escape_string($learn_more);
		$answer_query = "INSERT INTO answers_ext(media, type) VALUES ('%s', '%s')";
		$answer_query = sprintf($answer_query, $media0, $media0_type);
		$this->db_handle->queryExec($answer_query);
		$easy_id = $this->db_handle->lastInsertRowid();
		$answer_query = "INSERT INTO answers_ext(media, type) VALUES ('%s', '%s')";
		$answer_query = sprintf($answer_query, $media1, $media1_type);
		$this->db_handle->queryExec($answer_query);
		$medium_id = $this->db_handle->lastInsertRowid();
		$answer_query = "INSERT INTO answers_ext(media, type) VALUES ('%s', '%s')";
		$answer_query = sprintf($answer_query, $media2, $media2_type);
		$this->db_handle->queryExec($answer_query);
		$hard_id = $this->db_handle->lastInsertRowid();

		$answer_query = "INSERT INTO answers(answer, answer_type, topic_id, easy_id, medium_id, hard_id, learn_more) VALUES ('%s', '%s', '%d', '%d', '%d', '%d', '%s')";
		$answer_query = sprintf($answer_query, $answer, $answer_type, $topic_id, $easy_id, $medium_id, $hard_id, $learn_more);
		return $this->db_handle->queryExec($answer_query);
	}
	
	function getTopicAnswers($topic_id) {
		$topic_id = sqlite_escape_string($topic_id);
		$answer_query = "SELECT * FROM answers WHERE topic_id='" . $topic_id . "'";
		$result = $this->db_handle->query($answer_query);
		$answers = array();
		if ($result) {
			while ($answer = $result->fetch()) {
				$answers[] = $answer;
			}
		}
		return $answers;
	}
	
	function addTeamAnswer($num, $game_id, $answer_id) {
		$num = sqlite_escape_string($num);
		$game_id = sqlite_escape_string($game_id);
		$answer_id = sqlite_escape_string($answer_id);
	}
	
	function getTeamAnswersId($team_id) {
		$team_id = sqlite_escape_string($team_id);
		$answers_query = "SELECT answers FROM teams WHERE team_id='" . $team_id . "'";
		$result = $this->db_handle->query($answers_query);
		if ($result) {
			$answers = $result->fetch();
			return $answers["answers"];
		}
		return 0;
	}
	
	function getTeamCluesId($team_id) {
		$team_id = sqlite_escape_string($team_id);
		$clues_query = "SELECT clues FROM teams WHERE team_id='" . $team_id . "'";
		$result = $this->db_handle->query($clues_query);
		if ($result) {
			$clues = $result->fetch();
			return $clues["clues"];
		}
		return 0;
	}
	
/**
 * Games
 */	
	
	function getUserGames($user_id, $who) {
		
	}
	
	function createGame($team1_id, $team2_id, $topic_id) {
		$team1_id = sqlite_escape_string($team1_id);
		$team2_id = sqlite_escape_string($team2_id);
		$topic_id = sqlite_escape_string($topic_id);
		
		$game_query = "INSERT INTO games(team1_id, team2_id, score1, score2, turn1, turn2) VALUES ('%d', '%d', 0, 0, 1, 1)";
		$game_query = sprintf($game_query, $team1_id, $team2_id);
		if ($this->db_handle->queryExec($game_query)) {
			$game_id = $this->db_handle->lastInsertRowid();
			
			$this->clearTeamAnswers($team1_id);
			$this->clearTeamAnswers($team2_id);
			$this->clearTeamClues($team1_id);
			$this->clearTeamClues($team2_id);
			
			$answers = $this->getTopicAnswers($topic_id);
			$answers = array_splice($answers, 0, 10);
			$this->addTeamAnswers($team1_id, $answers);
			$this->addTeamAnswers($team2_id, $answers);
			
			return $game_id;
		}
		return false;
	}
	
	function addTeamAnswers($team_id, $answers) {
		$team_id = sqlite_escape_string($team_id);
		foreach ($answers as $answer) {
			foreach($answer as $k => $v) {
				$answer[$k] = sqlite_escape_string($v);
			}
		}
		
		$answers_query = "UPDATE team_answers SET ";
		foreach ($answers as $k => $v) {
			if ($k != 0) {
				$answers_query .= ", ";
			}
			$answers_query .= "answer" . ($k + 1) . "_id='" . $answers[$k]["answer_id"] . "' ";
		}
		$answers_query .= "WHERE team_id='" . $team_id . "'";
		
		$this->db_handle->queryExec($answers_query);
	}
	
	function getAnswer($answer_id, $difficulty) {
		$answer_id = sqlite_escape_string($answer_id);
		$difficulty = sqlite_escape_string($difficulty);
		
		if ($difficulty == 1) {
			$difficulty_query = "easy_id";
		} else if ($difficulty == 2) {
			$difficulty_query = "medium_id";
		} else if ($difficulty == 3) {
			$difficulty_query = "hard_id";
		}
		
		$answer_query = "SELECT answer_id, answer, answer_type, media, type, learn_more FROM answers JOIN answers_ext ON " . $difficulty_query . "=id WHERE answer_id='" . $answer_id . "'";
		$result = $this->db_handle->query($answer_query);
		if ($result) {
			return json_encode($result->fetch());
		}
		return false;
	}

	function clearTeamAnswers($team_id) {
		$answers_id = $this->getTeamAnswersId($team_id);
		if ($answers_id) {
			$answers_query = "UPDATE teams SET answers=NULL WHERE team_id='" . $team_id . "'; DELETE FROM team_answers WHERE id='" . $answers_id . "'";
			$this->db_handle->queryExec($answers_query);
		}
		$answers_query = "INSERT INTO team_answers(team_id) VALUES ('" . $team_id . "')";
		$this->db_handle->queryExec($answers_query);
		$answers_id = $this->db_handle->lastInsertRowid();
		$answers_query = "UPDATE teams SET answers='" . $answers_id . "' WHERE team_id='" . $team_id . "'";
		$this->db_handle->queryExec($answers_query);
	}
	
	function clearTeamClues($team_id) {			
		$clues_id = $this->getTeamCluesId($team_id);
		if ($clues_id) {
			$clues_query = "UPDATE teams SET clues=NULL WHERE team_id='" . $team_id . "'; DELETE FROM team_clues WHERE id='" . $clues_id . "'";
			$this->db_handle->queryExec($clues_query);
		}
		$clues_query = "INSERT INTO team_clues(team_id) VALUES ('" . $team_id . "')";
		$this->db_handle->queryExec($clues_query);
		$clues_id = $this->db_handle->lastInsertRowid();
		$clues_query = "UPDATE teams SET clues='" . $clues_id . "' WHERE team_id='" . $team_id . "'";
		$this->db_handle->queryExec($clues_query);
	}
	
	function addFriends($user_id, $friends) {
		$user_id = sqlite_escape_string($user_id);
		$friends_query = "";
		foreach ($friends as $friend) {
			if ($this->userExists($friend["id"])) {
				$friend_id = sqlite_escape_string($friend["id"]);
				$friends_query .= "INSERT INTO friends(friend1_id, friend2_id) VALUES ('$user_id', '$friend_id'); ";
				$friends_query .= "INSERT INTO friends(friend1_id, friend2_id) VALUES ('$friend_id', '$user_id'); ";
			}
		}
		if ($friends_query != "") {
			return $this->db_handle->queryExec($friends_query);
		}
		return false;
	}
	
	function getFriends($user_id) {
		$user_id = sqlite_escape_string($user_id);
		$friends_query = "SELECT friend2_id FROM friends JOIN users ON friend2_id=user_id WHERE friend1_id='" . $user_id . "' ORDER BY first_name";
		$result =  $this->db_handle->query($friends_query);
		$friends = array();
		if ($result) {
			while ($friend = $result->fetch()) {
				$friends[] = $friend;
			}
		}
		return json_encode($friends);
	}
	
	function teamExists($user1_id, $user2_id) {
		$user1_id = sqlite_escape_string($user1_id);
		$user2_id = sqlite_escape_string($user2_id);
		$team_query = "SELECT * FROM teams WHERE user1_id='%d' AND user2_id='%d'";
		$team_query = sprintf($team_query, $user1_id, $user2_id);
		if ($result = $this->db_handle->query($team_query)) {
			if ($result->numRows() > 0) {
				$team = $result->fetch();
				return $team["team_id"];
			}
		}
		return false;
	}
	
	function createTeam($user1_id, $user2_id) {
		$user1_id = sqlite_escape_string($user1_id);
		$user2_id = sqlite_escape_string($user2_id);
		$team_query = "INSERT INTO teams(user1_id, user2_id, state1, state2, in_game) VALUES ('%d', '%d', 1, 0, 0); ";
		$team_query = sprintf($team_query, $user1_id, $user2_id);
		return $this->db_handle->queryExec($team_query);
	}
	
	function updateTeam($team_obj) {
		
	}
	
	function getUserTeams($user_id) {
		$user_id = sqlite_escape_string($user_id);
		$team_query = "SELECT * FROM teams WHERE user1_id='" . $user_id . "' OR user2_id='" . $user_id . "'";
		$result = $this->db_handle->query($team_query);
		$teams = array();
		if ($result) {
			while ($row = $result->fetch()) {
				$teams[] = $row;
			}
		}
		return json_encode($teams);
	}
	
	function getPotentialTeams($team_id, $user1_id, $user2_id) {
		$team_id = sqlite_escape_string($team_id);
		$user1_id = sqlite_escape_string($user1_id);
		$user2_id = sqlite_escape_string($user2_id);
		$team_query = "SELECT * FROM teams WHERE team_id<>'%d' AND user1_id<>'%d' AND user2_id<>'%d' AND user1_id<>'%d' AND user2_id<>'%d'";
		$team_query = sprintf($team_query, $team_id, $user1_id, $user2_id, $user2_id, $user1_id);
		$result = $this->db_handle->query($team_query);
		$teams = array();
		if ($result) {
			while ($row = $result->fetch()) {
				$user_query = "SELECT first_name FROM users WHERE user_id=" . $row["user1_id"];
				$result = $this->db_handle->query($user_query);
				$user = $result->fetch();
				$row["user1_name"] = $user["first_name"];
				$user_query = "SELECT first_name FROM users WHERE user_id=" . $row["user2_id"];
				$result = $this->db_handle->query($user_query);
				$user = $result->fetch();
				$row["user2_name"] = $user["first_name"];
				$teams[] = $row;
			}
		}
		return json_encode($teams);
	}

	function getTeam($team_id) {
		$team_id = sqlite_escape_string($team_id);
		$team_query = "SELECT * FROM teams where team_id='" . $team_id . "'";
		$result = $this->db_handle->query($team_query);
		$team;
		if ($result) {
			$team = $result->fetch();
			$user_query = "SELECT first_name FROM users WHERE user_id=" . $team["user1_id"];
			$result = $this->db_handle->query($user_query);
			$user = $result->fetch();
			$team["user1_name"] = $user["first_name"];
			$user_query = "SELECT first_name FROM users WHERE user_id=" . $team["user2_id"];
			$result = $this->db_handle->query($user_query);
			$user = $result->fetch();
			$team["user2_name"] = $user["first_name"];
		}
		return json_encode($team);
	}
	
	function addTopic($topic) {
		$topic = sqlite_escape_string($topic);
		$topic_query = "INSERT INTO topics(topic) VALUES ('%s')";
		$topic_query = sprintf($topic_query, $topic);
		return $this->db_handle->queryExec($topic_query);
	}
	
	function getTopics() {
		$result = $this->db_handle->query("SELECT * FROM topics ORDER BY topic");
		$topics = array();
		if ($result) {
			while ($row = $result->fetch()) {
				$topics[] = $row;
			}
		}
		return json_encode($topics);
	}
	
	function getTopicName($topic_id) {
		$topic_id = sqlite_escape_string($topic_id);
		$topic_query = "SELECT topic FROM topics WHERE topic_id='" . $topic_id . "'";
		$result = $this->db_handle->query($topic_query);
		if ($result) {
			$topic = $result->fetch();
			return $topic["topic"];
		}
		return "";
	}
	
	function getTopicsSelect() {
		$result = $this->db_handle->query("SELECT * FROM topics");
		$topics_select = "";
		if ($result) {
			while ($row = $result->fetch()) {
				$topics_select .= '<option value="' . $row["topic_id"] . '">' . $row["topic"] . '</option>';
			}
		}
		return $topics_select;
	}
}

?>
