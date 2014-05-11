<?php 
require_once 'includes/QBCoref.class.php';
$qbc = new QBCoref();
?>
<!doctype html>
<html lang="us">
<head>
	<meta charset="utf-8">
	<title>Coreference Tool - Tutorial</title>
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
		$active_page = 'tutorial';
		require_once 'views/topnav.php';
	?>

    <div class="container">
    	<div class="row">
    		<div class="page-header">
				<h2>Tutorial</h2>
			</div>
			<?php if (isset($_GET['first']) && $_GET['first'] == 1) { ?>
			<p>
    		Welcome, <?php print $_SESSION['username']; ?>! We've noticed this is your 
    		first time logging in. Please take a few minutes and watch the following 
    		tutorial video which will help you understand how to get started. If you don't need help
    		go straight to the <a href="index.php">questions</a>.
    		</p>
    		<?php } ?>
    		<div align="center">
    			<iframe width="853" height="480" src="//www.youtube.com/embed/iIikEGvQULw" frameborder="0" allowfullscreen></iframe>
    		</div>
    		<?php if (isset($_GET['first']) && $_GET['first'] == 1) { ?>
    		<p class="text-center">
    		<br />
    		<a href="index.php">Skip Tutorial</a>
    		</p>
    		<?php } ?>
    	</div>	
	</div>
	<?php 
		require_once 'views/footer.php';
	?>
</body>
</html>




