<!doctype html>
<html>

<head>
	<meta charset="utf-8" />
	<title>Compare Templates</title>
	<script type="text/javascript" src="http://code.jquery.com/jquery.min.js" charset="utf-8"></script>
	<!--<script type="text/javascript" 
	src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>-->

	<link type="text/css" rel="stylesheet" href="style.css" />

	<script type="text/javascript" src="lib/codemirror.min.js"></script>
	<link type="text/css" rel="stylesheet" href="lib/codemirror.css" />

	<script type="text/javascript" src="lib/mergely.js"></script>
	<link type="text/css" rel="stylesheet" href="lib/mergely.css" />

	<link type="text/css" rel="stylesheet" href="lib/jquery-te-1.4.0.css">

	<script type="text/javascript" src="lib/jquery-te-1.4.0.min.js" charset="utf-8"></script>
</head>

</head>

<?php

// compare.php 
// Accepts LHS and RHS terms via GET and passes them to mergely to view

// 1.  Load LHS and RHS from GET
// 2.  Connect to mysql
// 3.  Search db for the two values
// 4.  Load up mergely with the two values

// ************************************************
// Pull LHS and RHS
// ************************************************

$LHS = $_GET['LHS'];
$RHS = $_GET['RHS'];

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
$queryLHS = $db->prepare("SELECT attending, title, template, id FROM `templates` WHERE `id` LIKE ?");
$queryLHS->bind_param('i', $LHS);
$queryLHS->execute();
$queryLHS->store_result();  // needed to find out how many rows returned
$queryLHS->bind_result($attdLHS, $titleLHS, $templateLHS, $idLHS);
while($queryLHS->fetch()) {
	// echo $templateLHS;	
}

$queryRHS = $db->prepare("SELECT attending, title, template, id FROM `templates` WHERE `id` LIKE ?");
$queryRHS->bind_param('i', $RHS);
$queryRHS->execute();
$queryRHS->store_result();  // needed to find out how many rows returned
$queryRHS->bind_result($attdRHS, $titleRHS, $templateRHS, $idRHS);
while($queryRHS->fetch()) {
	// echo $templateRHS;
}

$temp = nl2br($templateLHS);

$queryLHS->close();
$queryRHS->close();
$db->close();

?>

<body>
	<div class="title" id="Top_title">Comparing</div>
	<div class="title" id="LHS_title">Left title</div>
	<div class="title" id="RHS_title">Right title</div>
	<div id="compare"></div>
	<div id="buttons">
		<button id="markoff">Turn off Comparison</button>
		<button id="markon">Turn on Comparison</button>
		<button id="swap">Swap sides</button>
	</div>
	<div id="settings"></div>
	<script type="text/javascript">
	var LHS_TEXT = <?php echo json_encode($templateLHS); ?>;
	var RHS_TEXT = <?php echo json_encode($templateRHS); ?>;
	var LHS_title = <?php echo json_encode($titleLHS); ?>;
	var RHS_title = <?php echo json_encode($titleRHS); ?>;
	var LHS_attd = <?php echo json_encode($attdLHS); ?>;
	var RHS_attd = <?php echo json_encode($attdRHS); ?>;

	$(document).ready(function () {
		$('#compare').mergely({
			width: 'auto',
			height: 550,
			ignorews: true,
			cmsettings: { 
				readOnly: false, 
				lineNumbers: true,
				lineWrapping: true
			},
			lhs: function(setValue) {
				setValue(LHS_TEXT);
			},
			rhs: function(setValue) {
				setValue(RHS_TEXT);
			}
		});
		$('#LHS_title').html("<em>" + LHS_title + "</em>" + " by " + LHS_attd);
		$('#RHS_title').html("<em>" + RHS_title + "</em>" + " by " + RHS_attd);
		
		$('#markon').click(function () {
			$('#compare').mergely('update');
		});
		$('#markoff').click(function () {
			$('#compare').mergely('unmarkup');
		});

		$('#swap').click(function () {
			$('#compare').mergely('swap');
			var temp_title = LHS_title;
			var temp_attd = LHS_attd;
			LHS_title = RHS_title;
			RHS_title = temp_title;
			LHS_attd = RHS_attd;
			RHS_attd = temp_attd;
			$('#LHS_title').html("<em>" + LHS_title + "</em>" + " by " + LHS_attd);
			$('#RHS_title').html("<em>" + RHS_title + "</em>" + " by " + RHS_attd);
		});
		//$('#settings').mergely('options'));
	});
	</script>

</body>

</html>
