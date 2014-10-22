<?php 
require_once 'includes/QBCoref.class.php';
$qbc = new QBCoref();

if (!$qbc->isUser()) {
	header("Location: login.php");
	exit;
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
$show_accuracy = $qbc->isQuestionGoldStandard($qid);
$total_corefs = 0;
if ($show_accuracy) {
	$total_corefs = $qbc->getCountGoldStandardCorefs($qid);
}

$prevqid = $qbc->getPrevQuestion($qid);
$nextqid = $qbc->getNextQuestion($qid);

// Redirect if first login
if ($qbc->first_login == true) { 
	header("Location: tutorial.php?first=1");
	exit;
}

// Check for old version of IE
if ($qbc->isUsingOldIEBrowser()) {
	header("Location: unsupported.php");
	exit;
}
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
	  var show_accuracy = <?php print ($show_accuracy ? 1 : 0); ?>;
	  
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
    	<div class="row">
			<div class="col-md-9">
				<div class="row row1">
                    <?php
                    if ($qbc->isAdmin()) {
                        $theme['modal']['modal_id'] = 'question-stats';
                    ?>
                        <div class="question-stats pull-right">
                            <a href="#" data-toggle="modal" data-target="#<?php print $theme['modal']['modal_id']; ?>"><span class="glyphicon glyphicon-stats"></span> Statistics</a>
                        </div>
                        <?php
                            $theme['modal']['title'] = 'Question #' . $qid . ' Statistics';
                            $theme['modal']['body'] = $qbc->getQuestionStatistics($qid);
                            include_once 'views/modal.php';
                        ?>
                    <?php } ?>
                    <h4>Question #<?php print $qid; ?> <span id="saved-state">Saved...</span></h4>
                    <textarea id="question" readonly="readonly" ><?php print $question; ?>
					</textarea>
				</div>
				<div class="row">
					<div class="col-md-3">
						<input type="button" value="Undo [ctrl+z]" name="undo" id="undo" class="btn btn-default btn-sm"> 
					</div>
					<div class="col-md-4 text-center">
						<input type="button" value="Previous [p]" name="prev" id="prev" class="btn btn-default btn-sm"<?php if ($prevqid == PHP_INT_MAX) print " disabled=disabled"; ?>>
						<input type="button" value="Next [n]" name="next" id="next" class="btn btn-default btn-sm"<?php if ($nextqid == PHP_INT_MAX) print " disabled=disabled"; ?>>
					</div>
					<div class="col-md-4">
						<div class="pull-right">
							<?php if ($show_accuracy) { ?>
							<input type="button" value="Check Accuracy [c]" name="check_accuracy" id="check_accuracy" class="btn btn-default btn-sm">
							<?php } ?>
							<input type="button" value="Answer [a]" name="answer" id="answer" class="btn btn-default btn-sm">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12" style="width: 675px;">
						<h5 id="legend-title">Coreference Group Hotkeys</h5>
					</div>
				</div>
				<div id="legend">
					<div class="row">
						<div class="col-md-1"><div class="coref1 box"></div><div class="coref-key">1<span style="color: red;">**</span></div></div>
						<div class="col-md-1"><div class="coref2 box"></div><div class="coref-key">2</div></div>
						<div class="col-md-1"><div class="coref3 box"></div><div class="coref-key">3</div></div>
						<div class="col-md-1"><div class="coref4 box"></div><div class="coref-key">4</div></div>
						<div class="col-md-1"><div class="coref5 box"></div><div class="coref-key">5</div></div>
						<div class="col-md-1"><div class="coref6 box"></div><div class="coref-key">6</div></div>
						<div class="col-md-1"><div class="coref7 box"></div><div class="coref-key">7</div></div>
						<div class="col-md-1"><div class="coref8 box"></div><div class="coref-key">8</div></div>
						<div class="col-md-1"><div class="coref9 box"></div><div class="coref-key">9</div></div>
					</div>
					<div class="row" style="margin-top: 5px;">
						<div class="col-md-1"><div class="corefq box"></div><div class="coref-key">Q</div></div>
						<div class="col-md-1"><div class="corefw box"></div><div class="coref-key">W</div></div>
						<div class="col-md-1"><div class="corefe box"></div><div class="coref-key">E</div></div>
						<div class="col-md-1"><div class="corefr box"></div><div class="coref-key">R</div></div>
						<div class="col-md-1"><div class="coreft box"></div><div class="coref-key">T</div></div>
						<div class="col-md-1"><div class="corefy box"></div><div class="coref-key">Y</div></div>
						<div class="col-md-1"><div class="corefu box"></div><div class="coref-key">U</div></div>
						<div class="col-md-1"><div class="corefi box"></div><div class="coref-key">I</div></div>
						<div class="col-md-1"><div class="corefo box"></div><div class="coref-key">O</div></div>
					</div>
					<div class="row">
						<div class="col-md-12" style="text-align: left; margin-top: 10px; margin-left: 20px;"><span style="color: red;">**</span> - Use 1 when coreference relates to answer</div>
					</div>
				</div>
				<div id="answer_container" class="row alert alert-success">
					<div class="col-md-12">
						<h4>Answer</h4>
						<?php print $answer; ?>
					</div>
				</div>
				<div id="comment_container" class="row">
					<div class="col-md-11">
					<!-- customize labels of htmlcommentbox.com -->
					<script>
					// This code goes ABOVE the main HTML Comment Box code!
					// replace the text in the single quotes below to customize labels.
					hcb_user = {
					    //L10N
					    comments_header : 'Comments',
					    name_label : 'Name',
					    content_label: 'Enter your comment here',
					    submit : 'Comment',
					    logout_link : '<img title="log out" src="//www.htmlcommentbox.com/static/images/door_out.png" alt="[logout]" class="hcb-icon"/>',
					    admin_link : '<img src="//www.htmlcommentbox.com/static/images/door_in.png" alt="[login]" class="hcb-icon"/>',
					    no_comments_msg: 'No one has commented yet. Be the first!',
					    add:'Add your comment',
					    again: 'Post another comment',
					    rss:'<img src="//www.htmlcommentbox.com/static/images/feed.png" class="hcb-icon" alt="rss"/> ',
					    said:'said:',
					    prev_page:'<img src="//www.htmlcommentbox.com/static/images/arrow_left.png" class="hcb-icon" title="previous page" alt="[prev]"/>',
					    next_page:'<img src="//www.htmlcommentbox.com/static/images/arrow_right.png" class="hcb-icon" title="next page" alt="[next]"/>',
					    showing:'Showing',
					    to:'to',
					    website_label:'website (optional)',
					    email_label:'email',
					    anonymous:'Anonymous',
					    mod_label:'(mod)',
					    subscribe:'email me replies',
					    are_you_sure:'Do you want to flag this comment as inappropriate?',
					    
					    reply:'<img src="//www.htmlcommentbox.com/static/images/reply.png"/> reply',
					    flag:'<img src="//www.htmlcommentbox.com/static/images/flag.png"/> flag',
					    like:'<img src="//www.htmlcommentbox.com/static/images/like.png"/> like',
					    
					    //dates
					    days_ago:'days ago',
					    hours_ago:'hours ago',
					    minutes_ago:'minutes ago',
					    within_the_last_minute:'within the last minute',
					
					    msg_thankyou:'Thank you for commenting!',
					    msg_approval:'(this comment is not published until approved)',
					    msg_approval_required:'Thank you for commenting! Your comment will appear once approved by a moderator.',
					    
					    err_bad_html:'Your comment contained bad html.',
					    err_bad_email:'Please enter a valid email address.',
					    err_too_frequent:'You must wait a few seconds between posting comments.',
					    err_comment_empty:'Your comment was not posted because it was empty!',
					    err_denied:'Your comment was not accepted.',
					
					    //SETTINGS
					    MAX_CHARS: 2048,
					    PAGE:'<?php print $config['abs_path']; ?>index.php?qid='+qid, // ID of the webpage to show comments for. defaults to the webpage the user is currently visiting.
					    RELATIVE_DATES:true // show dates in the form "X hours ago." etc.
					};
					</script>
					<!-- done customizing labels of htmlcommentbox.com -->
					<!-- begin htmlcommentbox.com -->
					 <div id="HCB_comment_box"><a href="http://www.htmlcommentbox.com">Comment Box</a> is loading comments...</div>
					 <link rel="stylesheet" type="text/css" href="//www.htmlcommentbox.com/static/skins/bootstrap/twitter-bootstrap.css?v=0" />
					 <script type="text/javascript" id="hcb"> /*<!--*/ if(!window.hcb_user){hcb_user={};} (function(){var s=document.createElement("script"), l=(hcb_user.PAGE || ""+window.location), h="//www.htmlcommentbox.com";s.setAttribute("type","text/javascript");s.setAttribute("src", h+"/jread?page="+encodeURIComponent(l).replace("+","%2B")+"&mod=%241%24wq1rdBcg%24bpgdSA6XgKNLT1H36ALcV0"+"&opts=16862&num=10");if (typeof s!="undefined") document.getElementsByTagName("head")[0].appendChild(s);})(); /*-->*/ </script>
					<!-- end htmlcommentbox.com -->
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div id="results">
				<h4>Current Coreferences</h4>
					<div id="clear_all" style="text-align: center;">
						<button id="clear" class="btn btn-xs btn-danger" type="button" onclick="clearAll();">Clear All</button>
	  				</div>
	  				<?php if ($show_accuracy) { ?>
	  					<div class="accuracy text-center" style="font-style:normal;margin-left: 0;">Total Correct: <span id="num_correct">0</span>/<?php print $total_corefs; ?></div>
	  				<?php } ?>
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
	  				<div id="groupq-header" class="group-header">GROUP Q</div>
	  				<div id="groupq-results" class="group-results"></div>
	  				<div id="groupw-header" class="group-header">GROUP W</div>
	  				<div id="groupw-results" class="group-results"></div>
	  				<div id="groupe-header" class="group-header">GROUP E</div>
	  				<div id="groupe-results" class="group-results"></div>
	  				<div id="groupr-header" class="group-header">GROUP R</div>
	  				<div id="groupr-results" class="group-results"></div>
	  				<div id="groupt-header" class="group-header">GROUP T</div>
	  				<div id="groupt-results" class="group-results"></div>
	  				<div id="groupy-header" class="group-header">GROUP Y</div>
	  				<div id="groupy-results" class="group-results"></div>
	  				<div id="groupu-header" class="group-header">GROUP U</div>
	  				<div id="groupu-results" class="group-results"></div>
	  				<div id="groupi-header" class="group-header">GROUP I</div>
	  				<div id="groupi-results" class="group-results"></div>
	  				<div id="groupo-header" class="group-header">GROUP O</div>
	  				<div id="groupo-results" class="group-results"></div>
				</div>
			</div>
		</div>
		
	</div>
	<?php 
		require_once 'views/footer.php';
	?>
</body>
</html>
