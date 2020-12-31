<!DOCTYPE html>
<html>
<head>
	<title>Apeiro ~ Manage</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../lib/mdl/material.min.css">
	<script src="../lib/mdl/material.min.js"></script>
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
	<!-- Accent-colored raised button with ripple -->
	<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
 	 Button
	</button>
	<div id="container"/>
	<script>
 		var button = document.createElement('button');
 		var textNode = document.createTextNode('Click Me!');
  		button.appendChild(textNode);
  		button.className = 'mdl-button mdl-js-button mdl-js-ripple-effect';
  		componentHandler.upgradeElement(button);
  		document.getElementById('container').appendChild(button);
	</script>
</body>
</html>