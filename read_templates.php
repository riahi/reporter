<?php

	//$attending = $_POST['attending'];
	
	$attending = 'khorasani-ramin';

	// Connect to database
	$DB_NAME = 'cebi_reporter';
	$DB_HOST = 'mysql.stajmir.com';
	$DB_USER = 'cebi_ramin';
	$DB_PASS = 'bwhradiology';

	$db = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

	if ($db->connect_errno > 0) {
		printf("Connect failed: %s\n", $db->connect_error);
		exit();
	}
	else
		echo "Connected to MySQL <br />";

	$statement = $db->prepare("SELECT attending, title, template FROM templates WHERE attending = ?");
	$statement->bind_param('s', $attending);
	$statement->execute();
		
	$statement->bind_result($attd, $title, $template);

	while($statement->fetch()) {
		echo '<h2>' . $attd . '</h2>';
		echo '<h3>' . $title . '</h3>';
		echo '<p>' . nl2br($template) . '</p>';
	}

	$statement->close();
	$db->close();
?>