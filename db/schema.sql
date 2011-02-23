CREATE TABLE users (
	user_id INTEGER PRIMARY KEY,
	first_name TEXT NOT NULL,
	last_name TEXT NOT NULL,
	email TEXT UNIQUE NOT NULL
);

CREATE TABLE friends (
	friend1_id INTEGER REFERENCES users(user_id),
	friend2_id INTEGER REFERENCES users(user_id)
);

CREATE TABLE teams (
	team_id INTEGER PRIMARY KEY,
	user1_id INTEGER REFERENCES users(user_id),
	user2_id INTEGER REFERENCES users(user_id),
	answers INTEGER REFERENCES team_answers(id),
	clues INTEGER REFERENCES team_clues(id),
	state1 INTEGER,
	state2 INTEGER,
	in_game INTEGER NOT NULL,
	UNIQUE(user1_id, user2_id)
);

CREATE TABLE team_answers (
	id INTEGER PRIMARY KEY,
	team_id INTEGER UNIQUE NOT NULL,
	answer1_id INTEGER REFERENCES answers(answer_id),
	answer2_id INTEGER REFERENCES answers(answer_id),
	answer3_id INTEGER REFERENCES answers(answer_id),
	answer4_id INTEGER REFERENCES answers(answer_id),
	answer5_id INTEGER REFERENCES answers(answer_id),
	answer6_id INTEGER REFERENCES answers(answer_id),
	answer7_id INTEGER REFERENCES answers(answer_id),
	answer8_id INTEGER REFERENCES answers(answer_id),
	answer9_id INTEGER REFERENCES answers(answer_id),
	answer10_id INTEGER REFERENCES answers(answer_id)
);

CREATE TABLE team_clues (
	id INTEGER PRIMARY KEY,
	team_id INTEGER UNIQUE NOT NULL,
	clue1_id INTEGER REFERENCES clues(clue_id),
	clue2_id INTEGER REFERENCES clues(clue_id),
	clue3_id INTEGER REFERENCES clues(clue_id),
	clue4_id INTEGER REFERENCES clues(clue_id),
	clue5_id INTEGER REFERENCES clues(clue_id),
	clue6_id INTEGER REFERENCES clues(clue_id),
	clue7_id INTEGER REFERENCES clues(clue_id),
	clue8_id INTEGER REFERENCES clues(clue_id),
	clue9_id INTEGER REFERENCES clues(clue_id),
	clue10_id INTEGER REFERENCES clues(clue_id)
);

CREATE TABLE topics (
	topic_id INTEGER PRIMARY KEY,
	topic TEXT UNIQUE NOT NULL
);

CREATE TABLE games (
	game_id INTEGER PRIMARY KEY,
	team1_id INTEGER REFERENCES teams(team_id),
	team2_id INTEGER REFERENCES teams(team_id),
	score1 INTEGER NOT NULL,
	score2 INTEGER NOT NULL,
	turn1 INTEGER NOT NULL,
	turn2 INTEGER NOT NULL
);

CREATE TABLE answers (
	answer_id INTEGER PRIMARY KEY,
	answer TEXT NOT NULL,
	answer_type TEXT NOT NULL,
	topic_id INTEGER REFERENCES topics(topic_id),
	easy_id INTEGER REFERENCES answers_ext(id),
	medium_id INTEGER REFERENCES answers_ext(id),
	hard_id INTEGER REFERENCES answers_ext(id),
	learn_more TEXT NOT NULL
);

CREATE TABLE clues (
	clue_id INTEGER PRIMARY KEY,
	game_id INTEGER REFERENCES games(game_id),
	giver_id INTEGER REFERENCES users(user_id),
	receiver_id INTEGER REFERENCES users(user_id),
	answer_id INTEGER REFERENCES answers(answer_id)
);

CREATE TABLE answers_ext (
	id INTEGER PRIMARY KEY,
	media TEXT NOT NULL,
	type TEXT NOT NULL
);