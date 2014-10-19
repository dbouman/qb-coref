<?php 
require_once 'includes/QBCoref.class.php';
$qbc = new QBCoref();

if (!$qbc->isUser()) {
	header("Location: login.php");
}

if (!empty($_POST['action'])) {
	$errors = array();
	
	if ($_POST['action'] == "change-password") {
		if (!$qbc->verifyPassword($_POST['current-password'])) {
			$errors[] = "Current password doesn't match.";
		}
		else if (empty($_POST['password'])) {
			$errors[] = "Password can not be empty.";
		}
		else if ($_POST['password'] != $_POST['password-confirm']) {
			$errors[] = "Passwords do not match.";
		}
		
		if (empty($errors)) {
			$qbc->updatePassword($_POST['password']);
			header("Location: myaccount.php?success=1");
		}
	}
	
}
?>
<!doctype html>
<html lang="us">
<head>
	<meta charset="utf-8">
	<title>Coreference Tool - My Account</title>
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
		$active_page = 'myaccount';
		require_once 'views/topnav.php';
	?>

    <div class="container">
    	<div class="row">
    		<div class="page-header">
				<h2>My Account</h2>
				<?php
				if (!empty($errors)) {
					?>
					<div class="alert alert-danger">
					  <button type="button" class="close" data-dismiss="alert">&times;</button>
					  <strong>Warning!</strong> 
					  <ul>
					  	<?php 
						  	foreach ($errors as $error) {
						  		print "<li>".$error."</li>";	
						  	}
					  	?>
					  </ul>
					</div>
					<?php 
				} 
				
				if (!empty($_GET['success']) && $_GET['success'] == 1) {
				?>
					<div class="alert alert-success">
					  <button type="button" class="close" data-dismiss="alert">&times;</button>
					  <strong>Success!</strong> Your password has been successfully changed.
					</div>
				<?php 
				}
				?>
				<ul class="nav nav-tabs">
					<li class="active"><a href="#password" data-toggle="tab">Change Password</a></li>
				</ul>
			</div>
    		<div class="col-md-8 col-md-offset-2">
				<!-- Tab panes -->
				<div class="tab-content">
					<div class="tab-pane active" id="password">
						<form class="form-horizontal" method="post" role="form">
						<input type="hidden" name="action" value="change-password">
						<div class="form-group">
							<label for="username" class="col-sm-4 control-label">Current Password</label>
		    				<div class="col-sm-8">
								<input id="current-password" name="current-password" type="password" placeholder="Current Password" class="form-control input-md">
							</div>
						</div>
						<div class="form-group">
							<label for="password" class="col-sm-4 control-label">New Password</label>
		    				<div class="col-sm-8">
								<input id="password" name="password" type="password" placeholder="New Password" class="form-control input-md">
							</div>
						</div>
						<div class="form-group">
							<label for="password-confirm" class="col-sm-4 control-label">Confirm New Password</label>
		    				<div class="col-sm-8">
								<input id="password-confirm" name="password-confirm" type="password" placeholder="Confirm New Password" class="form-control input-md">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-8">
								<button type="submit" id="submit" name="submit" class="btn btn-primary">Change Password</button>
							</div>
						</div>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php 
		require_once 'views/footer.php';
	?>
</body>
</html>
