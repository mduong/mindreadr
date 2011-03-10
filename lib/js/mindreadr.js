/**
 * @author Michael Duong
 */
function createGame(topic_id, user1_id, user2_id) {
	$.ajax({
		type: "POST",
		url: "http://cgi.stanford.edu/~mduong/ed196x/actions/games/create.php",
		data: "topic_id=" + topic_id + "&user1_id=" + user1_id + "&user2_id=" + user2_id,
		success: function(data) {
			$.mobile.changePage("http://cgi.stanford.edu/~mduong/ed196x/views/play.php?game_id=" + data);
		}
	});
}

//function createGame(topic_id, team1_id, team2_id) {
//	$.ajax({
//		type: "POST",
//		url: "../actions/games/create.php",
//		data: "topic_id=" + topic_id + "&team1_id=" + team1_id + "&team2_id=" + team2_id,
//		success: function(data) {
//			$.mobile.changePage("views/play.php?game_id=" + data + "&team_id=" + team1_id);
//		}
//	});
//}

//function chooseDifficulty(game_id, team_id, user_id, difficulty) {
//	$.ajax({
//		type: "POST",
//		url: "../actions/games/choose_difficulty.php",
//		data: "game_id=" + game_id + "&team_id=" + team_id + "&user_id=" + user_id + "&difficulty=" + difficulty,
//		success: function(data) {
//			$.mobile.changePage("views/give_clue.php?game_id=" + game_id + "&team_id=" + team_id + "&answer_id=" + data + "&difficulty=" + difficulty);
//		}
//	});
//}

function chooseDifficulty(game_id, user_id, difficulty) {
	$.ajax({
		type: "POST",
		url: "http://cgi.stanford.edu/~mduong/ed196x/actions/games/choose_difficulty.php",
		data: "game_id=" + game_id + "&user_id=" + user_id + "&difficulty=" + difficulty,
		success: function(data) {
			$.mobile.changePage("http://cgi.stanford.edu/~mduong/ed196x/views/give_clue.php?game_id=" + game_id + "&answer_id=" + data + "&difficulty=" + difficulty);
		}
	});
}

//function validateGuess(game_id, team_id, clue_id, user_id, points) {
//	guess = $('#guess').val();
//	$.ajax({
//		type: "POST",
//		url: "../actions/games/guess.php",
//		data: "game_id=" + game_id + "&team_id=" + team_id + "&clue_id=" + clue_id + "&user_id=" + user_id + "&points=" + points + "&guess=" + guess,
//		success: function(data) {
//			if (data != 0) {
//				alert('You got it!');
//				$.mobile.changePage("views/got_right.php?game_id=" + game_id + "&team_id=" + team_id + "&answer_id=" + data);
//			} else {
//				alert('Try again.');
//			}
//		}
//	});
//}

function validateGuess(game_id, clue_id, user_id, answer_id) {
	guess = $('#guess').val();
	$.ajax({
		type: "POST",
		url: "http://cgi.stanford.edu/~mduong/ed196x/actions/games/guess.php",
		data: "game_id=" + game_id + "&clue_id=" + clue_id + "&user_id=" + user_id + "&points=" + $("#points").html() + "&guess=" + guess,
		success: function(data) {
			if (data != 0) {
				alert('You got it!');
				$.mobile.changePage("http://cgi.stanford.edu/~mduong/ed196x/views/got_right.php?game_id=" + game_id + "&answer_id=" + data);
			} else {
				var points = $("#points").html();
				points -= 100;
				$("#points").html(points);
				if (points == 0)
					$.mobile.changePage("http://cgi.stanford.edu/~mduong/ed196x/views/got_wrong.php?game_id=" + game_id + "&answer_id=" + answer_id);
				else
					alert('Try again.');
				$('#guess').val("");
			}
		}
	});
}

function revealAnswer(answer_id, difficulty) {
	$.ajax({
		type: "POST",
		url: "http://cgi.stanford.edu/~mduong/ed196x/actions/games/reveal.php",
		data: "answer_id=" + answer_id,
		success: function(data) {
			$("#reveal_btn").remove();
			$("#answer_container").html("<strong>Revealed:</strong> " + data);
			var points = $("#points").html();
			if (difficulty == 1) points -= 1000;
			else if (difficulty == 2) points -= 1500;
			else if (difficulty == 3) points -= 2000;
			$("#points").html(points);
			$("[name=points]").val(points);
		}
	});
}

function continueGame(game_id) {
	window.location = "http://cgi.stanford.edu/~mduong/ed196x/views/play.php?game_id=" + game_id;
}