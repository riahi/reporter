<?php

	$attending = $_POST['attending'];
	$title = $_POST['title'];
	$template = $_POST['template'];

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

	$statement = $db->prepare("INSERT INTO templates(attending,title,template) 
		VALUES (?, ?, ?)");
	$statement->bind_param('sss', $attending, $title, $template);

	$statement->execute();
	$statement->close();


?>