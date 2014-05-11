<?php
require_once 'includes/QBCoref.class.php';
$qbc = new QBCoref();

if (!$qbc->isAdmin()) {
	die("You don't have access to this page.");
}

function array2csv($array)
{
	if (count($array) == 0) {
		return null;
	}
	ob_start();
	$df = fopen("php://output", 'w');
	$headers = array();
	$headers[] = 'cid';
	$headers[] = 'qid';
	$headers[] = 'pos_start';
	$headers[] = 'pos_end';
	$headers[] = 'description';
	$headers[] = 'coref_group';
	$headers[] = 'author';
	$headers[] = 'date_added';
	fputcsv($df, $headers);
	foreach ($array as $row) {
		fputcsv($df, $row);
	}
	fclose($df);
	return ob_get_clean();
}

function download_send_headers($filename) {
	// disable caching
	$now = gmdate("D, d M Y H:i:s");
	header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
	header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
	header("Last-Modified: {$now} GMT");

	// force download
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");

	// disposition / encoding on response body
	header("Content-Disposition: attachment;filename={$filename}");
	header("Content-Transfer-Encoding: binary");
}

download_send_headers("coref_export_" . date("Y-m-d") . ".csv");
echo array2csv($qbc->getAllCorefs());
die();