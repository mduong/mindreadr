CREATE TABLE users (
	user_id INTEGER PRIMARY KEY,
	first_name TEXT NOT NULL,
	last_name TEXT NOT NULL,
	email TEXT UNIQUE NOT NULL,
	password TEXT NOT NULL,
	phone INTEGER UNIQUE NOT NULL
);

CREATE TABLE friends (
	friend1_id INTEGER REFERENCES users(user_id),
	friend2_id INTEGER REFERENCES users(user_id)
);

CREATE TABLE teams (
	user1_id INTEGER REFERENCES users(user_id),
	user2_id INTEGER REFERENCES users(user_id)
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
	turn2 INTEGER NOT NULL,
	clues TEXT NOT NULL
);

CREATE TABLE answers (
	answer_id INTEGER PRIMARY KEY,
	answer TEXT NOT NULL,
	answer_type TEXT NOT NULL,
	difficulty INTEGER NOT NULL,
	media_id INTEGER REFERENCES media(media_id)
);

CREATE TABLE clues (
	clue_id INTEGER PRIMARY KEY,
	game_id INTEGER REFERENCES games(game_id),
	giver_id INTEGER REFERENCES users(user_id),
	receiver_id INTEGER REFERENCES users(user_id),
	answer_id INTEGER REFERENCES answers(answer_id)
);

CREATE TABLE media (
	media_id INTEGER PRIMARY KEY,
	media TEXT NOT NULL,
	type TEXT NOT NULL
);
