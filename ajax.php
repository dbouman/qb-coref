<?php
require_once 'includes/QBCoref.class.php';
$qbc = new QBCoref();

if ($_GET['action'] == 'save') {
	$qid = $_POST['qid'];
	$author = $_POST['author'];
	$qbc->deleteCoreferences($qid, $author);
	foreach ($_POST as $key => $coref) {
		if (is_numeric($key)) {
			$pos_start = $coref['pos']['start'];
			$pos_end = $coref['pos']['end'];
			$description = addslashes($coref['description']);
			$coref_group = $coref['type'];
			
			$qbc->saveCoref($qid, $author, $pos_start, $pos_end, $description, $coref_group);
		}
	}
	print $qbc->getPoints();
}
else if ($_GET['action'] == 'accuracy') {
	$qid = $_POST['qid'];
	$pos_start = $_POST['pos']['start'];
	$pos_end = $_POST['pos']['end'];
	$description = addslashes($_POST['description']);
	
	$accuracy = $qbc->getAccuracy($qid, $description, $pos_start, $pos_end);
	
	if ($accuracy) {
		print "<span class=\"glyphicon glyphicon-ok\"></span> Correct";
	}
	else {
		print "<span class=\"glyphicon glyphicon-remove\"></span> No match for this tag was found.";
	}
}
?>