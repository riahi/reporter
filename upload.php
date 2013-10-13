<?php
// upload.php 
// Very crappy version.  Does no error checking,
// safety checking, nothing!  It's just for learning purposes.  Now for
// uploading exports.

// 1.  Open file
// 2.  Connect to mysql
// 3.  Process file
// 4.  Put into database

// ************************************************
// Open file
// ************************************************

// Accesses uploaded temporary file.  File is deleted after script is done.
$contents = file_get_contents($_FILES['file']['tmp_name']);

// ************************************************
// Connects to mysql and builds query
// ************************************************
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

// Starts building query
$statement = $db->prepare("INSERT INTO templates(attending, title, template) 
	VALUES (?, ?, ?)");

// ************************************************
// Process file
// ************************************************

// Need attending, template name, template text
// use pathinfo() to get attending from filename without extension
$path_parts = pathinfo($_FILES['file']['name']);

$attending = $path_parts['filename'];
// $attending = $_FILES['file']['name'];

// debug
echo "<h2>Attending name: " . $attending . "</h2>";

// Splits apart file based on regex.  
// PHP regex requires / .. / on either side of pattern
// returns an array that drops all empty matches
$array = preg_split('/\$\$\$\r?\n/', $contents, -1, PREG_SPLIT_NO_EMPTY);
echo "<p>Array after split</p>";
echo "<pre>"; print_r($array); echo "</pre>";

// ************************************************
// Process and put into Database
// ************************************************
// loops through array and parses.  Ignores last array slot.
$title = '';
$template = '';
for($i = 0; $i < count($array); $i++) {
	$temp = $array[$i];

	preg_match_all('/(.+)(\r?\n)((\W|\w)+)/', $temp, $matches, PREG_SET_ORDER);
	$tempArray = $matches[0];
	$title = $tempArray[1];
	$template = $tempArray[3];

	//debug
	echo "<h3>Title: " . $title . "</h3>";
	echo "<p>" . nl2br($template) . "</p>";
	echo "================== <br />";

	// bind values to pre-defined statement and execute it, inputting into database
	$statement->bind_param('sss', $attending, $title, $template);
	$statement->execute();

}

$statement->close();
$db->close();



?>