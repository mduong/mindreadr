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
			$.mobile.changePage("views/play.php?game_id=10");
		}
	});
}