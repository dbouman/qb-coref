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
	$times_tagged = $_POST['times_tagged'];
	
	$accuracy = $qbc->getAccuracy($qid, $description, $pos_start, $pos_end);
	$percent = round(($accuracy / $times_tagged) * 100);
	
	print "Accuracy: " .$percent."% (" . $accuracy . "/" . $times_tagged . ")";
}
?>