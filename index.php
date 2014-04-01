<?php 
require_once 'includes/QBCoref.class.php';
$qbc = new QBCoref();

if (!$qbc->isUser()) {
	header("Location: login.php");
}

$qid = $qbc->getLastQuestion();
if (!empty($_GET['qid']) && is_numeric($_GET['qid'])) {
	$qid = $_GET['qid']; 
}

// Save last qid accessed for user
$qbc->updateLastQuestion($qid);

$question = $qbc->getQuestion($qid);
$answer = $qbc->getAnswer($qid);
$corefs = $qbc->getCorefsAsJSON($qid, $_SESSION['username']);

$prevqid = $qbc->getPrevQuestion($qid);
$nextqid = $qbc->getNextQuestion($qid);
?>
<!doctype html>
<html lang="us">
<head>
	<meta charset="utf-8">
	<title>Coreference Tool</title>
	<link href="css/smoothness/jquery-ui-1.10.4.custom.css" rel="stylesheet">
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
	<link href="css/coref.css" rel="stylesheet">
	<script src="js/jquery-1.10.2.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery-ui-1.10.4.custom.js"></script>
	<script src="js/jquery.selection.js"></script>
	<script src="js/jquery.hotkeys.js"></script>
	<script src="js/coref-tagging.js"></script>
	<script type="text/javascript">
	  // global variables, used by coref-tagging.js
	  var question_id = '#question';
	  var annotation_container = '#results';
	  var qid = <?php print $qid; ?>;
	  var prevqid = <?php print $prevqid; ?>;
	  var nextqid = <?php print $nextqid; ?>;
	  var total_questions = <?php print $qbc->getTotalQuestions(); ?>;
	  
	  $(document).ready(function(){
		  <?php 
		  	if (!empty($corefs)) {
		  ?>
		  		json_tags = <?php print $corefs; ?>;
		  		for (var i=0;i<json_tags.length;i++) {
			  		var tag = json_tags[i];
			  		if (tag) {
			  			createExistingTag(tag['id'], tag['annotation'], tag['pos'], tag['type'])
			  		}
		  		}
		  <?php 
			} 
		  ?>

		  tags.qid = qid;
		  tags.author = "<?php print $_SESSION['username']; ?>";
		  
		  initShortcuts();
	  });
	</script>
</head>
<body>
	<?php 
		$active_page = 'questions';
		require_once 'views/topnav.php';
	?>

    <div class="container">
		<?php if ($qbc->first_login == true) { ?>
			<div class="alert alert-info alert-dismissable">
			  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			  <strong>Welcome <?php print $_SESSION['username']; ?>!</strong> It appears this is your first time
			  here. If you're not sure what to do, please visit our <a href="help.php">getting started</a> page.
			</div>
		<?php } ?>
    	<div class="row row1">
			<div class="col-md-9">
				<h4>Question #<?php print $qid; ?> <span id="saved-state">Saved...</span></h4>
				<textarea id="question" readonly="readonly" ><?php print $question; ?>
				</textarea>
			</div>
			<div class="col-md-3">
				<div id="results">
				<h4>Current Coreferences</h4>
					<div id="clear_all" style="text-align: center;">
						<button id="clear" class="btn btn-xs btn-danger" type="button" onclick="clearAll();">Clear All</button>
	  				</div>
	  				<div id="group1-header" class="group-header">GROUP 1</div>
	  				<div id="group1-results" class="group-results"></div>
	  				<div id="group2-header" class="group-header">GROUP 2</div>
	  				<div id="group2-results" class="group-results"></div>
	  				<div id="group3-header" class="group-header">GROUP 3</div>
	  				<div id="group3-results" class="group-results"></div>
	  				<div id="group4-header" class="group-header">GROUP 4</div>
	  				<div id="group4-results" class="group-results"></div>
	  				<div id="group5-header" class="group-header">GROUP 5</div>
	  				<div id="group5-results" class="group-results"></div>
	  				<div id="group6-header" class="group-header">GROUP 6</div>
	  				<div id="group6-results" class="group-results"></div>
	  				<div id="group7-header" class="group-header">GROUP 7</div>
	  				<div id="group7-results" class="group-results"></div>
	  				<div id="group8-header" class="group-header">GROUP 8</div>
	  				<div id="group8-results" class="group-results"></div>
	  				<div id="group9-header" class="group-header">GROUP 9</div>
	  				<div id="group9-results" class="group-results"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-9">
				<input type="button" value="Undo [ctrl+z]" name="undo" id="undo" class="btn btn-default"> 
				<input type="button" value="Previous [p]" name="prev" id="prev" class="btn btn-default"<?php if ($prevqid == PHP_INT_MAX) print " disabled=disabled"; ?>>
				<input type="button" value="Next [n]" name="next" id="next" class="btn btn-default"<?php if ($nextqid == PHP_INT_MAX) print " disabled=disabled"; ?>>
				<input type="button" value="Answer [a]" name="answer" id="answer" class="btn btn-default">
			</div>
		</div>
		<div class="row">
			<div class="col-md-9" style="width: 675px;">
				<h5 id="legend-title">Coreference Group Hotkeys</h5>
			</div>
		</div>
		<div id="legend" class="row">
			<div class="col-md-1"><div class="coref1 box"></div><div class="coref-key">1<span style="color: red;">**</span></div></div>
			<div class="col-md-1"><div class="coref2 box"></div><div class="coref-key">2</div></div>
			<div class="col-md-1"><div class="coref3 box"></div><div class="coref-key">3</div></div>
			<div class="col-md-1"><div class="coref4 box"></div><div class="coref-key">4</div></div>
			<div class="col-md-1"><div class="coref5 box"></div><div class="coref-key">5</div></div>
			<div class="col-md-1"><div class="coref6 box"></div><div class="coref-key">6</div></div>
			<div class="col-md-1"><div class="coref7 box"></div><div class="coref-key">7</div></div>
			<div class="col-md-1"><div class="coref8 box"></div><div class="coref-key">8</div></div>
			<div class="col-md-1"><div class="coref9 box"></div><div class="coref-key">9</div></div>
			<div class="col-md-9" style="text-align: left; margin-top: 10px; margin-left: 20px;"><span style="color: red;">**</span> - Use 1 when coreference relates to answer</div>
		</div>
		<div id="answer_container" class="row alert alert-success">
			<div class="col-md-9">
				<h4>Answer</h4>
				<?php print $answer; ?>
			</div>
		</div>
	</div>
</body>
</html>
