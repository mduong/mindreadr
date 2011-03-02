<?php

include_once("config.php");

class MindReadrDb {
	
	public $STATE_NONE = 0;
	public $STATE_DIFFICULTY = 1;
	public $STATE_GIVE_CLUE = 2;
	public $STATE_DONE_CLUE = 3;
	public $STATE_GUESS = 4;
	public $STATE_DONE_GUESS = 5;
	public $STATE_WAIT_CLUE = 6;
	
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
	function createGame($team1_id, $team2_id, $topic_id) {
		$team1_id = sqlite_escape_string($team1_id);
		$team2_id = sqlite_escape_string($team2_id);
		$topic_id = sqlite_escape_string($topic_id);
		
		$game_query = "INSERT INTO games(team1_id, team2_id) VALUES ('%d', '%d')";
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
			
			$this->setTeamGameId($team1_id, $game_id);
			$this->setTeamGameId($team2_id, $game_id);
			$this->setTeamTurn($team1_id, 1);
			$this->setTeamTurn($team2_id, 1);
			$this->setTeamScore($team1_id, 0);
			$this->setTeamScore($team2_id, 0);
			
			$team1 = json_decode($this->getTeam($team1_id));
			$team2 = json_decode($this->getTeam($team2_id));
			
			$this->setState($game_id, $team1->{"user1_id"}, $this->STATE_DIFFICULTY);
			$this->setState($game_id, $team1->{"user2_id"}, $this->STATE_WAIT_CLUE);
			$this->setState($game_id, $team2->{"user1_id"}, $this->STATE_DIFFICULTY);
			$this->setState($game_id, $team2->{"user2_id"}, $this->STATE_WAIT_CLUE);
			
			return $game_id;
		}
		return false;
	}

	function getGiveClueGames($user_id) {
		$user_id = sqlite_escape_string($user_id);
		
		$games_query = "SELECT DISTINCT T.team_id AS team_id, T.user1_id AS user1_id, T.user2_id AS user2_id, T.game_id AS game_id, T.team1_id AS team1_id, T.team2_id AS team2_id, T.difficulty AS difficulty, S.state AS state, T.turn AS turn, T.score AS score FROM (SELECT * FROM teams JOIN games ON teams.game_id=games.game_id) T JOIN (SELECT * FROM states WHERE user_id='" . $user_id . "') S ON (T.user1_id=S.user_id OR T.user2_id=S.user_id) WHERE (T.user1_id='" . $user_id . "' OR T.user2_id='" . $user_id . "') AND (state='1' OR state='2')";
		$result = $this->db_handle->query($games_query);
		$games = array();
		while ($game = $result->fetch()) {
			$games[] = $game;
		}
		return json_encode($games);
	}
	
	function getGuessAnswerGames($user_id) {
		$user_id = sqlite_escape_string($user_id);
		
		$games_query = "SELECT DISTINCT T.team_id AS team_id, T.user1_id AS user1_id, T.user2_id AS user2_id, T.game_id AS game_id, T.team1_id AS team1_id, T.team2_id AS team2_id, T.difficulty AS difficulty, S.state AS state, T.turn AS turn, T.score AS score FROM (SELECT * FROM teams JOIN games ON teams.game_id=games.game_id) T JOIN (SELECT * FROM states WHERE user_id='" . $user_id . "') S ON (T.user1_id=S.user_id OR T.user2_id=S.user_id) WHERE (T.user1_id='" . $user_id . "' OR T.user2_id='" . $user_id . "') AND state='4'";
		$result = $this->db_handle->query($games_query);
		$games = array();
		while ($game = $result->fetch()) {
			$games[] = $game;
		}
		return json_encode($games);
	}

	function setTeamGameId($team_id, $game_id) {
		$team_id = sqlite_escape_string($team_id);
		$game_id = sqlite_escape_string($game_id);
		$teams_query = "UPDATE teams SET game_id='" . $game_id . "' WHERE team_id='" . $team_id . "'";
		$this->db_handle->queryExec($teams_query);
	}
	
	/**
	 * Gets the team turn for the given team, 0 if
	 * error.
	 */
	function getTeamTurn($team_id) {
		$team_id = sqlite_escape_string($team_id);
		$turn_query = "SELECT turn FROM teams WHERE team_id='" . $team_id . "'";
		$result = $this->db_handle->query($turn_query);
		if ($result) {
			$turn = $result->fetch();
			return $turn["turn"];
		}
		return 0;
	}
	
	/**
	 * Sets the team turn for the given team.
	 */
	function setTeamTurn($team_id, $turn) {
		$team_id = sqlite_escape_string($team_id);
		$turn = sqlite_escape_string($turn);
		$teams_query = "UPDATE teams SET turn='" . $turn . "' WHERE team_id='" . $team_id . "'";
		$this->db_handle->queryExec($teams_query);
	}
	
	/**
	 * Sets the team score for the given team.
	 */
	function setTeamScore($team_id, $score) {
		$team_id = sqlite_escape_string($team_id);
		$score = sqlite_escape_string($score);
		$teams_query = "UPDATE teams SET score='" . $score . "' WHERE team_id='" . $team_id . "'";
		$this->db_handle->queryExec($teams_query);
	}
	
	/**
	 * Returns a JSON-encoded version of the game with
	 * game_id.
	 */
	function getGame($game_id) {
		$game_id = sqlite_escape_string($game_id);
		$game_query = "SELECT * FROM games WHERE game_id='" . $game_id . "'";
		$result = $this->db_handle->query($game_query);
		if ($result) {
			return json_encode($result->fetch());
		}
		return false;
	}
	
	/**
	 * Sets the user's state in the given game. Creates a
	 * new state row in the DB if it does not already
	 * exist.
	 */
	function setState($game_id, $user_id, $state) {
		$game_id = sqlite_escape_string($game_id);
		$user_id = sqlite_escape_string($user_id);
		$state = sqlite_escape_string($state);
		
		if ($this->getState($game_id, $user_id) == 0) {
			$state_query = "INSERT INTO states(game_id, user_id, state) VALUES ('%d', '%d', '%d')";
			$state_query = sprintf($state_query, $game_id, $user_id, $state);
		} else {		
			$state_query = "UPDATE states SET state='" . $state . "' WHERE game_id='" . $game_id . "' AND user_id='" . $user_id . "'";
		}
		$this->db_handle->queryExec($state_query);
	}
	
	/**
	 * Gets the user's state in the given game. Returns 0 if
	 * the DB entry does not exist.
	 */
	function getState($game_id, $user_id) {
		$game_id = sqlite_escape_string($game_id);
		$user_id = sqlite_escape_string($user_id);
		
		$state_query = "SELECT state FROM states WHERE game_id='" . $game_id . "' AND user_id='" . $user_id . "'";
		$result = $this->db_handle->query($state_query);
		if ($result && sizeof($result) > 0) {
			$state = $result->fetch();
			return $state["state"];
		}
		return 0;
	}
	
	function setDifficulty($game_id, $team_id, $user_id, $difficulty) {
		$game_id = sqlite_escape_string($game_id);
		$team_id = sqlite_escape_string($team_id);
		$user_id = sqlite_escape_string($user_id);
		$difficulty = sqlite_escape_string($difficulty);
		
		$query = "UPDATE teams SET difficulty='%d' WHERE team_id='%d'";
		$query = sprintf($query, $difficulty, $team_id);
		$this->db_handle->queryExec($query);
		
		$query = "SELECT * FROM teams WHERE team_id='" . $team_id . "'";
		$result = $this->db_handle->query($query);
		if ($result) {
			$team = $result->fetch();
			$answers = $team["answers"];
			$turn = $team["turn"];
			$query = "SELECT answer" . $turn . "_id FROM team_answers WHERE id='" . $answers . "'";
			$result = $this->db_handle->query($query);
			if ($result) {
				$answer_id = $result->fetch();
				$answer_id = $answer_id["answer" . $turn . "_id"];
				$this->setState($game_id, $user_id, $this->STATE_GIVE_CLUE);
				return $answer_id;
			}
		}
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
	
	function getAnswerNoId($team_id, $difficulty, $turn) {
		$team_id = sqlite_escape_string($team_id);
		$difficulty = sqlite_escape_string($difficulty);
		$turn = sqlite_escape_string($turn);
		
		$answer_query = "SELECT team_answers.answer" . $turn . "_id AS answer_id FROM team_answers JOIN teams ON teams.team_id=team_answers.team_id WHERE teams.team_id='" . $team_id . "'";
		$result = $this->db_handle->query($answer_query);
		if ($result) {
			$answer = $result->fetch();
			$answer_id = $answer["answer_id"];
			return $this->getAnswer($answer_id, $difficulty);
		}
		return 0;
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
	
	function getAnswerReveal($answer_id) {
		$answer_id = sqlite_escape_string($answer_id);
		$answer_query = "SELECT answer FROM answers WHERE answer_id='" . $answer_id . "'";
		$result = $this->db_handle->query($answer_query);
		if ($result) {
			$answer = $result->fetch();
			return $answer["answer"];
		}
		return false;
	}		
	
	function getClueNoId($team_id, $turn) {
		$team_id = sqlite_escape_string($team_id);
		$turn = sqlite_escape_string($turn);
		
		$clue_query = "SELECT team_clues.clue" . $turn . "_id AS clue_id FROM team_clues JOIN teams ON teams.team_id=team_clues.team_id WHERE teams.team_id='" . $team_id . "'";
		$result = $this->db_handle->query($clue_query);
		if ($result) {
			$clue = $result->fetch();
			$clue_id = $clue["clue_id"];
			return $this->getClue($clue_id);
		}
		return 0;
	}
	
	function getClue($clue_id) {
		$clue_id = sqlite_escape_string($clue_id);
		$clue_query = "SELECT * FROM clues WHERE clue_id='" . $clue_id . "'";
		$result = $this->db_handle->query($clue_query);
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
			$clues_query = "UPDATE teams SET clues=NULL WHERE team_id='" . $team_id . "'; DELETE FROM team_clues WHERE team_id='" . $team_id . "'";
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
		$team_query = "SELECT * FROM teams WHERE (user1_id='%d' AND user2_id='%d') OR (user1_id='%d' AND user2_id='%d')";
		$team_query = sprintf($team_query, $user1_id, $user2_id, $user2_id, $user1_id);
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
		$team_query = "INSERT INTO teams(user1_id, user2_id) VALUES ('%d', '%d'); ";
		$team_query = sprintf($team_query, $user1_id, $user2_id);
		return $this->db_handle->queryExec($team_query);
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
			$user_query = "SELECT first_name FROM users WHERE user_id='" . $team["user1_id"] . "'";
			$result = $this->db_handle->query($user_query);
			$user = $result->fetch();
			$team["user1_name"] = $user["first_name"];
			$user_query = "SELECT first_name FROM users WHERE user_id='" . $team["user2_id"] . "'";
			$result = $this->db_handle->query($user_query);
			$user = $result->fetch();
			$team["user2_name"] = $user["first_name"];
		}
		return json_encode($team);
	}
	
	function getTeammate($team_id, $user_id) {
		$team_id = sqlite_escape_string($team_id);
		$user_id = sqlite_escape_string($user_id);
		
		$team = json_decode($this->getTeam($team_id));
		$teammate_id = ($user_id == $team->{"user1_id"}) ? $team->{"user2_id"} : $team->{"user1_id"};
		$teammate_query = "SELECT * FROM users WHERE user_id='" . $teammate_id . "'";
		if ($result = $this->db_handle->query($teammate_query)) {
			return json_encode($result->fetch());
		}
		return 0; 
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
	
	function recordClue($game_id, $team_id, $user_id, $answer_id, $clue, $points) {
		$game_id = sqlite_escape_string($game_id);
		$team_id = sqlite_escape_string($team_id);
		$user_id = sqlite_escape_string($user_id);
		$answer_id = sqlite_escape_string($answer_id);
		$clue = sqlite_escape_string($clue);
		$points = sqlite_escape_string($points);
		
		$teammate = json_decode($this->getTeammate($team_id, $user_id));
		$teammate_id = $teammate->{"user_id"};
		
		$clue_query = "INSERT INTO clues(clue, game_id, giver_id, receiver_id, answer_id, points) VALUES('%s', '%d', '%d', '%d', '%d', '%d')";
		$clue_query = sprintf($clue_query, $clue, $game_id, $user_id, $teammate_id, $answer_id, $points);
		$this->db_handle->queryExec($clue_query);
		$clue_id = $this->db_handle->lastInsertRowid();
		
		$turn = $this->getTeamTurn($team_id);
		$clues_query = "UPDATE team_clues SET clue" . $turn . "_id='" . $clue_id . "' WHERE team_id='" . $team_id . "'";
		$this->db_handle->queryExec($clues_query);
		
		$this->setState($game_id, $user_id, $this->STATE_DONE_CLUE);
		$this->setState($game_id, $teammate_id, $this->STATE_GUESS);
	}

	function validateGuess($game_id, $team_id, $user_id, $clue_id, $guess, $points) {
		$game_id = sqlite_escape_string($game_id);
		$team_id = sqlite_escape_string($team_id);
		$user_id = sqlite_escape_string($user_id);
		$clue_id = sqlite_escape_string($clue_id);
		$guess = sqlite_escape_string($guess);
		$points = sqlite_escape_string($points);
		
		$answer_id = 0;
		
		$validate_query = "SELECT * FROM clues JOIN answers ON clues.answer_id=answers.answer_id WHERE clue_id='" . $clue_id . "'";
		$result = $this->db_handle->query($validate_query);
		if ($result) {
			$result = $result->fetch();
			$answer = $result["answers.answer"];
			if (strcasecmp($guess, $answer) == 0) {
				$answer_id = $result["answers.answer_id"];	
				$teammate = json_decode($this->getTeammate($team_id, $user_id));
				$teammate_id = $teammate->{"user_id"};
				
				$team = json_decode($this->getTeam($team_id));
				$score = $team->{"score"} + points;
				$turn = $team->{"turn"} + 1;
				
				$update_query = "UPDATE teams SET score='" . $score . "', turn='" . $turn . "' WHERE team_id='" . $team_id . "'";
				$this->db_handle->queryExec($update_query);
				
				$this->setState($game_id, $user_id, $this->STATE_DIFFICULTY);
				$this->setState($game_id, $teammate_id, $this->STATE_DONE_GUESS);
			}
		}
		
		$guess_query = "INSERT INTO guesses(game_id, user_id, answer_id, clue_id, guess) VALUES('%d', '%d', '%d', '%d', '%s')";
		$guess_query = sprintf($guess_query, $game_id, $user_id, $answer_id, $clue_id, $guess);
		$this->db_handle->queryExec($guess_query);
		
		return $answer_id;
	}
	
	function getScore($team_id) {
		$team_id = sqlite_escape_string($team_id);
		$score_query = "SELECT score FROM teams WHERE team_id='" . $team_id . "'";
		$result = $this->db_handle->query($score_query);
		if ($result) {
			$score = $result->fetch();
			return $score["score"];
		}
		return 0;
	}
}

?>
