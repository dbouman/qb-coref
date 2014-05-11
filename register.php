<?php 
require_once 'includes/QBCoref.class.php';
$qbc = new QBCoref();
$qbc->logout();

// initialize variables
$username="";
$firstname="";
$lastname="";
$email="";

if (!empty($_POST['action']) && $_POST['action'] == "register") {
	$username = filter_input(INPUT_POST,'username',FILTER_SANITIZE_STRING);
	$firstname = filter_input(INPUT_POST,'first-name',FILTER_SANITIZE_STRING);
	$lastname = filter_input(INPUT_POST,'last-name',FILTER_SANITIZE_STRING);
	$email = $_POST['email'];
	
	$errors = array();
	
	if (empty($username)) {
		$errors[] = "Please include a username.";
	}
	else if ($username != $_POST['username']) {
		$errors[] = "Invalid characters, please pick another username.";
	}
	else if (!$qbc->isUsernameUnique($username)) {
		$errors[] = "Duplicate username detected, please try another username.";
	}
	
	if (empty($_POST['password'])) {
		$errors[] = "Password can not be empty.";
	}
	else if ($_POST['password'] != $_POST['password-confirm']) {
		$errors[] = "Passwords do not match.";
	}
	
	if (empty($firstname)) {
		$errors[] = "Please include your first name.";
	}
	
	if (empty($lastname)) {
		$errors[] = "Please include your last name.";
	}
	
	if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$errors[] = "Invalid email address.";
	}
	
	if (empty($errors)) {
		$qbc->register($username, $_POST['password'], $firstname, $lastname, $email);
		header("Location: login.php?success=true");
	}
}
?>
<!doctype html>
<html lang="us">
<head>
<meta charset="utf-8">
<title>Coreference Tool - Register</title>
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

button#cancel {
	margin-left: 10px;
}
</style>
</head>
<body>
	<?php 
		$active_page = 'register';
		require_once 'views/topnav.php';
	?>

	<div class="container">
		<div class="row">
			<div class="page-header">
				<h2>Register</h2>
			</div>
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
			?>
			<div class="col-md-9">
				<form class="form-horizontal" method="post" role="form">
					<input type="hidden" name="action" value="register">
					<div class="form-group">
						<label for="username" class="col-sm-4 control-label">Username</label>
	    				<div class="col-sm-8">
							<input id="username" name="username" type="text" placeholder="Username" class="form-control input-md" value="<?php print $username; ?>">
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-sm-4 control-label">Password</label>
	    				<div class="col-sm-8">
							<input id="password" name="password" type="password" placeholder="Password" class="form-control input-md">
						</div>
					</div>
					<div class="form-group">
						<label for="password-confirm" class="col-sm-4 control-label">Confirm Password</label>
	    				<div class="col-sm-8">
							<input id="password-confirm" name="password-confirm" type="password" placeholder="Confirm Password" class="form-control input-md">
						</div>
					</div>
					<div class="form-group">
						<label for="first-name" class="col-sm-4 control-label">First Name</label>
	    				<div class="col-sm-8">
							<input id="first-name" name="first-name" type="text" placeholder="First Name" class="form-control input-md" value="<?php print $firstname; ?>">
						</div>
					</div>
					<div class="form-group">
						<label for="last-name" class="col-sm-4 control-label">Last Name</label>
	    				<div class="col-sm-8">
							<input id="last-name" name="last-name" type="text" placeholder="Last Name" class="form-control input-md" value="<?php print $lastname; ?>">
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-sm-4 control-label">Email</label>
	    				<div class="col-sm-8">
							<input id="email" name="email" type="text" placeholder="Email" class="form-control input-md" value="<?php print $email; ?>">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-8">
							<button type="submit" id="register" name="register" class="btn btn-primary">Register</button>
							<button type="button" id="cancel" name="cancel" onclick="window.location='login.php';" class="btn btn-default">Cancel</button>
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
