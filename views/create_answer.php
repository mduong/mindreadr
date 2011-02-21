<!DOCTYPE HTML>
<html>
	<head>
		<script src="../jqtouch/jquery.1.3.2.min.js"></script>
		<script src="../lib/js/jquery.form.js"></script>

		<style>
			span.bold {
				font-weight: bold;
			}
		</style>
	</head>
	<body>

		<h2>Create Answers</h2>
		
		<div id="create_answer">
			<form id="answer_form" action="../actions/answers/create.php" method="POST" enctype="multipart/form-data">
				<p>
					<span class="bold">Answer</span>: <input type="text" name="answer" /><br />
					<span class="bold">Type</span>: <select name="answer_type">
						<option value="Person">Person</option>
						<option value="Place">Place</option>
						<option value="Thing">Thing</option>
						<option value="Idea">Idea</option>
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
	</body>

	<script type="text/javascript">
	
		$(document).ready(function() {
			
			$('#answer_form').ajaxForm({
				clearForm: 'true',
				success: function() {
					var block = document.getElementById('easy');
					var remove = document.getElementById('media0');
					if (remove) {
						block.removeChild(remove);
					}
					block = document.getElementById('medium');
					remove = document.getElementById('media1');
					if (remove) {
						block.removeChild(remove);
					}
					block = document.getElementById('hard');
					remove = document.getElementById('media2');
					if (remove) {
						block.removeChild(remove);
					}
					alert("Thank you!");
				}
			});
		});
		
		
		$('input[name="media0_type"]').change(function() {
			var block = document.getElementById('easy');
			var remove = document.getElementById('media0');
			if (remove) {
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
			if (remove) {
				block.removeChild(remove);
			}
			var input = document.createElement('input');
			input.id = 'media1';
			if ($('input[name="media1_type"]:checked').val() == "text") {
				input.type = 'text';
				input.name = 'media1';
			} else if ($('input[name="media1_type"]:checked').val() == "image") {
				input.type = 'file';
				input.name = 'media[]';
			}
			block.appendChild(input);
		});
		
		$('input[name="media2_type"]').change(function() {
			var block = document.getElementById('hard');
			var remove = document.getElementById('media2');
			if (remove) {
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
</html>
