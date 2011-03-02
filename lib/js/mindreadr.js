/**
 * @author Michael Duong
 */
function createTeam(topic_id, user1_id, user2_id) {
	$.ajax({
		type: "POST",
		url: "../actions/teams/create.php",
		data: "user1_id=" + user1_id + "&user2_id=" + user2_id,
		success: function(data) {
			$.mobile.changePage("views/teams.php?topic_id=" + topic_id + "&user1_id=" + user1_id + "&user2_id=" + user2_id + "&team_id=" + data);
		}
	});
}

function createGame(topic_id, team1_id, team2_id) {
	$.ajax({
		type: "POST",
		url: "../actions/games/create.php",
		data: "topic_id=" + topic_id + "&team1_id=" + team1_id + "&team2_id=" + team2_id,
		success: function(data) {
			$.mobile.changePage("views/play.php?game_id=" + data + "&team_id=" + team1_id);
		}
	});
}

function chooseDifficulty(game_id, team_id, user_id, difficulty) {
	$.ajax({
		type: "POST",
		url: "../actions/games/choose_difficulty.php",
		data: "game_id=" + game_id + "&team_id=" + team_id + "&user_id=" + user_id + "&difficulty=" + difficulty,
		success: function(data) {
			$.mobile.changePage("views/give_clue.php?game_id=" + game_id + "&team_id=" + team_id + "&answer_id=" + data + "&difficulty=" + difficulty);
		}
	});
}

function validateGuess(game_id, team_id, clue_id, user_id, points) {
	guess = $('#guess').val();
	$.ajax({
		type: "POST",
		url: "../actions/games/guess.php",
		data: "game_id=" + game_id + "&team_id=" + team_id + "&clue_id=" + clue_id + "&user_id=" + user_id + "&points=" + points + "&guess=" + guess,
		success: function(data) {
			if (data != 0) {
				alert('You got it!');
				$.mobile.changePage("views/got_right.php?game_id=" + game_id + "&team_id=" + team_id + "&answer_id=" + data);
			} else {
				alert('Try again.');
			}
		}
	});
}

function revealAnswer(answer_id) {
	$.ajax({
		type: "POST",
		url: "../actions/games/reveal.php",
		data: "answer_id=" + answer_id,
		success: function(data) {
			$("#reveal_btn").remove();
			$("#answer_container").html("<strong>Revealed:</strong> " + data);
		}
	});
}

function continueGame(game_id, team_id) {
	window.location = "http://cgi.stanford.edu/~mduong/ed196x/views/play.php?game_id=" + game_id + "&team_id=" + team_id;
}