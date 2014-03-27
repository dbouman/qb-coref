<?php 
require_once '../includes/QBCoref.class.php';
$qbc = new QBCoref();

$count = 0;
if ($_GET['load'] == "questions") {
	$count = $qbc->loadQuestions();
}
else if ($_GET['load'] == "answers") {
	$count = $qbc->loadAnswers();
}

print "Done inserting " . $count . " rows";
?>