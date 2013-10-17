<!--
<?php
// view.php
// Used to view single templates.  Will eventually include rating functionality.
?>-->
<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<title>View Template</title>
	<script type="text/javascript" src="http://code.jquery.com/jquery.min.js" charset="utf-8"></script>

	<link type="text/css" rel="stylesheet" href="style.css" />
</head>
</head>

<?php 
// 1.  Load templateid from GET
// 2.  Connect to mysql
// 3.  Search db for the id
// 4.  Pretty print out the template

// ************************************************
// Pull template ID
// ************************************************

$id = $_GET['id'];

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

// Builds query, executes it, and binds results
$sql = $db->prepare("SELECT attending, title, template FROM `templates` WHERE `id` LIKE ?");
$sql->bind_param('i', $id);
$sql->execute();
$sql->store_result();  // needed to find out how many rows returned
$sql->bind_result($attd, $title, $template);
while($sql->fetch()) {
}
$sql->close();
$db->close();
?>

<body>
	<header>
		<h1>Viewing <strong><?php echo $title;?></strong> by <em><?php echo $attd;?></em></h1>
	</header>

	<nav>
		<button>Button</button>
		<form>
			<input type="radio" name="indication_status" value="Approved" />
			<label for="indication_status">Approved</label>
			<input type="radio" name="indication_status" value="Disapproved" />
			<label for="indication_status">Disapproved</label>
			<input type="radio" name="indication_status" value="Needs Work" />
			<label for="indication_status">Needs Work</label>
		</form>
	</nav>

	<article>
		<pre class="templatebox"><?php echo $template; ?></pre>
	</article>

	<footer>
		Footer
	</footer>
</body>
</html>