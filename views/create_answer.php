<script src="../lib/js/jquery-1.5.min.js"></script>
<script src="../lib/js/jquery.form.js"></script>

<style>
	span.bold {
		font-weight: bold;
	}
</style>

<h2>Create Answers</h2>

<div id="create_answer">
	<form id="answer_form" method="POST" enctype="multipart/form-data">
		<p>
			<span class="bold">Answer</span>: <input type="text" name="answer" /><br />
			<span class="bold">Type</span>: <select name="answer_type">
				<option value="person">Person</option>
				<option value="place">Place</option>
				<option value="thing">Thing</option>
				<option value="idea">Idea</option>
			</select>
		</p>
		<p id="easy">
			<span class="bold">Difficulty</span>: Easy<br />
			<span class="bold">Media type</span>:
			<input type="radio" name="media0_type" value="text" />Text
			<input type="radio" name="media0_type" value="image" />Image<br />
		</p>
		<p id="medium">
			<span class="bold">Difficulty</span>: Medium<br />
			<span class="bold">Media type</span>:
			<input type="radio" name="media1_type" value="text" />Text
			<input type="radio" name="media1_type" value="image" />Image<br />
		</p>
		<p id="hard">
			<span class="bold">Difficulty</span>: Hard<br />
			<span class="bold">Media type</span>:
			<input type="radio" name="media2_type" value="text" />Text
			<input type="radio" name="media2_type" value="image" />Image<br />
		</p>
		<input type="submit" value="Submit">
	</form>
</div>

<script type="text/javascript">

	$(document).ready(function() {
		$('#answerForm').ajaxForm({
			clearForm: 'true',
			success: function() {
				alert("Thank you!");
			}
		});
	});
	
	$('input[name="media0_type"]').change(function() {
		var block = document.getElementById('easy');
		var remove = document.getElementById('media0');
		if (remove != undefined) {
			block.removeChild(remove);
		}
		var input = document.createElement('input');
		input.id = 'media0';
		if ($('input[name="media0_type"]:checked').val() == "text") {
			input.type = 'text';
			input.name = 'media0';
		} else if ($('input[name="media0_type"]:checked').val() == "image") {
			input.type = 'file';
			input.name += 'media[]';
		}
		block.appendChild(input);
	});
	
	$('input[name="media1_type"]').change(function() {
		var block = document.getElementById('medium');
		var remove = document.getElementById('media1');
		if (remove != undefined) {
			block.removeChild(remove);
		}
		var input = document.createElement('input');
		input.id = 'media1';
		if ($('input[name="media1_type"]:checked').val() == "text") {
			input.type = 'text';
		input.name = 'media1';
		} else if ($('input[name="media1_type"]:checked').val() == "image") {
			input.type = 'file';
			input.type = 'media[]';
		}
		block.appendChild(input);
	});
	
	$('input[name="media2_type"]').change(function() {
		var block = document.getElementById('hard');
		var remove = document.getElementById('media2');
		if (remove != undefined) {
			block.removeChild(remove);
		}
		var input = document.createElement('input');
		input.id = 'media2';
		if ($('input[name="media2_type"]:checked').val() == "text") {
			input.type = 'text';
			input.name = 'media2';
		} else if ($('input[name="media2_type"]:checked').val() == "image") {
			input.type = 'file';
			input.name = 'media[]';
		}
		block.appendChild(input);
	});
	
</script>