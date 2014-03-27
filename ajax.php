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
?>