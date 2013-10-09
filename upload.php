<?php
// upload.php Used to upload files Very crappy version.  Does no error checking,
// safety checking, nothing!  It's just for learning purposes.  Now for
// uploading exports.

// Accesses uploaded temporary file.  File is deleted after script is done.
$contents = file_get_contents($_FILES['file']['tmp_name']);

// prints out contents. nl2br will preserve line breaks in the HTML
// echo nl2br($contents);

$array = preg_split('/\$\$\$\r?\n/', $contents);

for($i = 0; $i < count($array); $i++) {
	echo nl2br($array[$i]);
	echo "================== <br />";
}

?>