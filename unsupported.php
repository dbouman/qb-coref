<?php 
require_once 'includes/QBCoref.class.php';
$qbc = new QBCoref();

if (!$qbc->isUser()) {
	header("Location: login.php");
}
?>
<!doctype html>
<html lang="us">
<head>
<meta charset="utf-8">
<title>Coreference Tool - Unsupported Browser</title>
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
	$active_page = 'unsupported';
	require_once 'views/topnav.php';
	?>

	<div class="container">
		<div class="row">
			<div class="page-header">
				<h2>Unsupported Browser</h2>
			</div>
			It appears you are using a browser that is not supported by our site.
			We recommend using one of the following supported browsers:



			<div class="text-center">
				<ul id="_ul" style="list-style: none; padding: 0px 0px 0px 10px;">
					<li onclick="window.location='http://www.microsoft.com/windows/Internet-explorer/default.aspx'" id="_li1"
						style='background: url("img/background_browser.gif") no-repeat left top; margin: 0px 10px 10px 0px; width: 120px; height: 122px; float: left; cursor: pointer;'>
						<div
							id="_ico1"
							style='background: url("img/browser_ie.gif") no-repeat left top; margin: 1px auto; width: 100px; height: 100px;'></div>
						<div id="_lit1"
							style="margin: 1px auto; width: 118px; height: 18px; text-align: center; color: rgb(128, 128, 128); line-height: 17px; font-size: 0.8em;">Internet
							Explorer 10+</div></li>
					<li onclick="window.location='http://www.mozilla.com/firefox/'" id="_li2"
						style='background: url("img/background_browser.gif") no-repeat left top; margin: 0px 10px 10px 0px; width: 120px; height: 122px; float: left; cursor: pointer;'>
						<div
							id="_ico2"
							style='background: url("img/browser_firefox.gif") no-repeat left top; margin: 1px auto; width: 100px; height: 100px;'></div>
						<div id="_lit2"
							style="margin: 1px auto; width: 118px; height: 18px; text-align: center; color: rgb(128, 128, 128); line-height: 17px; font-size: 0.8em;">Firefox
							28+</div></li>
					<li onclick="window.location='http://www.google.com/chrome'" id="_li5"
						style='background: url("img/background_browser.gif") no-repeat left top; margin: 0px 10px 10px 0px; width: 120px; height: 122px; float: left; cursor: pointer;'>
						<div
							id="_ico5"
							style='background: url("img/browser_chrome.gif") no-repeat left top; margin: 1px auto; width: 100px; height: 100px;'></div>
						<div id="_lit5"
							style="margin: 1px auto; width: 118px; height: 18px; text-align: center; color: rgb(128, 128, 128); line-height: 17px; font-size: 0.8em;">Chrome
							34+</div></li>
				</ul>
			</div>
	</div>
	<?php 
		require_once 'views/footer.php';
	?>
</body>
</html>





