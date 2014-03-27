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
	<title>Coreference Tool - Leaderboard</title>
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
		$active_page = 'leaderboard';
		require_once 'views/topnav.php';
	?>

    <div class="container">
    	<div class="row">
    		<div class="page-header">
				<h2>Leaderboard</h2>
				Top 10 highest ranked users. Don't see your name on the list? Keep adding more coreferences!!!
			</div>
    		<div class="col-md-8 col-md-offset-2">
				<table class="table">
					<thead>
						<tr>
							<th>Rank</th>
							<th>Username</th>
							<th>Points</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$count = 1;
							foreach ($leaderboard as $leader) {
								?>
								<tr<?php if($count==1) { print ' class="success"'; } ?>>
									<td>#<?php print $count; ?></td>
									<td><?php print $leader[0]; ?></td>
									<td><?php print $leader[1]; ?></td>
								</tr>
								<?php 
								$count++;
							} 
						?>
					</tbody>
				</table>
			</div>
		</div>
		<p><a href="index.php">&laquo; Back to Questions</a></p>
	</div>
</body>
</html>
