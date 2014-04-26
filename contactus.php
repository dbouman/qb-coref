<?php 
require_once 'includes/QBCoref.class.php';
$qbc = new QBCoref();

$success = 0;
$error = 0;

// initialize variables
$fromName = "";
$fromEmail = "";
$message = "";

if (!empty($_POST['action']) && $_POST['action'] == "contactus") {
	if (!empty($_POST['mail'])) {
		$error = 1;
	}
	else {
		$fromName = filter_input(INPUT_POST,'InputName',FILTER_SANITIZE_STRING);
		$fromEmail = filter_input(INPUT_POST,'InputEmail',FILTER_SANITIZE_STRING);
		$message = filter_input(INPUT_POST,'InputMessage',FILTER_SANITIZE_STRING);
		
		if (empty($fromName) || empty($fromEmail) || empty($message)) {
			$error = 1;
		}
		else {
			$result = $qbc->sendEmail($fromName, $fromEmail, $message);
			if ($result) {
				$success = 1;
				$fromName = "";
				$fromEmail = "";
				$message = "";
			}
			else {
				$error = 1;
			}
		}
	}
}
?>
<!doctype html>
<html lang="us">
<head>
	<meta charset="utf-8">
	<title>Coreference Tool - Contact Us</title>
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
    	</div>
		<div class="row">
		  <div class="col-md-12">
		  <?php if ($success == 1) { ?>
			<div class="alert alert-success"><strong><span class="glyphicon glyphicon-send"></span> Success! Message sent.</strong></div>
		  <?php }
		  else if ($error == 1) { 
		  ?>	  
		    <div class="alert alert-danger"><span class="glyphicon glyphicon-alert"></span><strong> Error! Please make sure you've filled out all the required fields.</strong></div>
		  <?php } ?>
		  </div>
		  <form role="form" method="post" >
		  	<input type="hidden" name="action" value="contactus">
		    <div class="col-lg-6">
		      <div class="well well-sm"><strong><i class="glyphicon glyphicon-ok form-control-feedback"></i> Required Field</strong></div>
		      <div class="form-group">
		        <label for="InputName">Your Name</label>
		        <div class="input-group">
		          <input type="text" class="form-control" name="InputName" id="InputName" placeholder="Enter Name" value="<?php print $fromName; ?>" required>
		          <span class="input-group-addon"><i class="glyphicon glyphicon-ok form-control-feedback"></i></span></div>
		      </div>
		      <div class="form-group">
		        <label for="InputEmail">Your Email</label>
		        <div class="input-group">
		          <input type="email" class="form-control" id="InputEmail" name="InputEmail" placeholder="Enter Email" value="<?php print $fromEmail; ?>" required  >
		          <span class="input-group-addon"><i class="glyphicon glyphicon-ok form-control-feedback"></i></span></div>
		      </div>
		      <div class="form-group">
		        <label for="InputMessage">Message</label>
		        <div class="input-group">
		          <textarea name="InputMessage" id="InputMessage" class="form-control" rows="5" required><?php print $message; ?></textarea>
		          <span class="input-group-addon"><i class="glyphicon glyphicon-ok form-control-feedback"></i></span></div>
		      </div>
		      <div class="form-group" style="display:none;">
		        <div class="input-group">
		          <input type="text" class="form-control" name="mail" id="mail">
		        </div>
		      </div>
		      <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-info pull-right">
		    </div>
		  </form>
		</div>
	</div>
	<?php 
		require_once 'views/footer.php';
	?>
</body>
</html>




