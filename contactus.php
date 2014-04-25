<?php 
require_once 'includes/QBCoref.class.php';
$qbc = new QBCoref();

if (!$qbc->isUser()) {
	header("Location: login.php");
}

$limit = 10;
$leaderboard = $qbc->getLeaderboard($limit);
?>
<!doctype html>
<html lang="us">
<head>
	<meta charset="utf-8">
	<title>Coreference Tool - Getting Started</title>
	<link href="css/smoothness/jquery-ui-1.10.4.custom.css" rel="stylesheet">
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
	<link href="css/coref.css" rel="stylesheet">
	<script src="js/jquery-1.10.2.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery-ui-1.10.4.custom.js"></script>
</head>
<body>
	<?php 
		$active_page = 'contactus';
		require_once 'views/topnav.php';
	?>

    <div class="container">
    	<div class="row">
    		<div class="page-header">
				<h2>Contact Us</h2>
			</div>
    		Coming soon...
    	</div>
	</div>
	<?php 
		require_once 'views/footer.php';
	?>
</body>
</html>




