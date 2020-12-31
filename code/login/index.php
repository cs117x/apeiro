<html>
<head>
	<link rel="stylesheet" href="../lib/mdl/material.min.css">
	<script src="../lib/mdl/material.min.js"></script>
	<link rel="stylesheet" type="text/css" href="login.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<title>Apeiro | Login</title>
</head>
<body>
	<center>
		<div id="focus">
			<form action="./pin_activation.php" method="post">
  				<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    				<input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" name="employee_number">
    				<label class="mdl-textfield__label" for="sample4">Employee Number...</label>
    				<span class="mdl-textfield__error">That is not a number!</span>
  				</div>
  				<br>
  			  	<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    				<input class="mdl-textfield__input" type="password" name="password">
    				<label class="mdl-textfield__label" for="sample3">Password...</label>
  				</div>
  				<br>
  				<button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-js-ripple-effect" name="try">
 					 Activate pin
				</button>
				<br>
				<button class="mdl-button mdl-js-button mdl-button--primary" name="forget" id="forget">
  					Request password reset
				</button>
			</form>
		</div>
	</center>
</body>
</html>
