	<form action="search.php" method="post">
	<p>Search template titles: <input type="text" name="title_search" /></p>
	<input type="submit" value="Search Titles" />
	</form>

<?php

// search.php 
// Starts with a simple search box that searches the template title.  
// Displays the responses.  

// 1.  Pull out search term from search.htm
// 2.  Connect to mysql
// 3.  Search db
// 4.  Display list of results
// 5.  Link to a text viewer for viewing reports

// ************************************************
// Pull search term from form
// ************************************************

$title_search = $_POST['title_search'];

// ************************************************
// Connects to mysql, builds query, and searches
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

// Builds query, executes it, and binds results
$query = $db->prepare("SELECT attending, title, template, id FROM `templates` WHERE `title` LIKE CONCAT('%', ?, '%')");
$query->bind_param('s', $title_search);
$query->execute();
$query->store_result();  // needed to find out how many rows returned

$query->bind_result($attd, $title, $template, $id);

// ************************************************
// Display list of results
// ************************************************
$row_count = $query->num_rows;
echo $row_count . " rows returned for search <em>" . $title_search . "</em>";
?>
<form action="compare.php" method="get">
	<table>
		<tr>
			<td>LHS</td>
			<td>RHS</td>
			<td>Title</td>
			<td>Attending</td>
		</tr>
<?php
// loops through array and prints.
while($query->fetch()) {
	echo '<tr>
			<td><input type="radio" value="'. $id .'" name="LHS" />LHS</td>
			<td><input type="radio" value="'. $id .'" name="RHS" />RHS</td>
			<td><a href="view.php?id='. $id . '">' . $title . '</a></td>
			<td>' . $attd . '</td>
		</tr>';
}

echo '</table>';
echo '<input type="submit" value="Compare Templates" />';
echo '</form>';

$query->close();
$db->close();

?>