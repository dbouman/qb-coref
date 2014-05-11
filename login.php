<?php 
require_once 'includes/QBCoref.class.php';
$qbc = new QBCoref();
$qbc->logout();

if (!empty($_POST['username']) && !empty($_POST['password'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	$result = $qbc->login($username, $password);
	
	if ($result) {
		header("Location: index.php");
	}
	else {
		$error = "<strong>Invalid login</strong>. Please try again.";
	}
}
?>
<!doctype html>
<html lang="us">
<head>
<meta charset="utf-8">
<title>Coreference Tool - Login</title>
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
<link href="css/coref.css" rel="stylesheet">
<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.min.js"></script>
<style type="text/css">
body {
	padding-top: 60px;
	padding-bottom: 40px;
}

.login-links {
	margin-top: 30px;
}
</style>
</head>
<body>
	<?php 
		$active_page = 'login';
		require_once 'views/topnav.php';
	?>

	<div class="container">
		<div class="row">
			<div class="page-header">
				<h2>Login</h2>
			</div>
			<?php
			if (!empty($error)) {
				?>
				<div class="alert alert-danger">
				  <button type="button" class="close" data-dismiss="alert">&times;</button>
				  <?php print $error; ?>
				</div>
				<?php 
			} 
			if (!empty($_GET['success']) && $_GET['success'] == 'true') {
				?>
				<div class="alert alert-success">
				  <button type="button" class="close" data-dismiss="alert">&times;</button>
				  <strong>Congratulations!</strong> Your account has been successfully registered, please login below.
				</div>
				<?php 
			}
			?>
			<div class="col-md-7">
				<form class="form-horizontal" method="post" role="form" action="login.php">
					<div class="form-group">
						<label for="username" class="col-sm-2 control-label">Username</label>
	    				<div class="col-sm-10">
							<input id="username" name="username" type="text" placeholder="Username" class="form-control input-md">
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-sm-2 control-label">Password</label>
	    				<div class="col-sm-10">
							<input id="password" name="password" type="password" placeholder="Password" class="form-control input-md">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" id="login" name="login" class="btn btn-success">Login</button>
							<div class="login-links">
								<a href="register.php">Register for an account</a>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php 
		require_once 'views/footer.php';
	?>
</body>
</html>
