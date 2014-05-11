<div id="footer" class="navbar navbar-default">
	<div class="container">
		<div class="collapse navbar-collapse pull-left">
			<ul class="nav navbar-nav">
				<li><a href="help.php">FAQs</a></li>
				<li><a href="tutorial.php">Tutorial</a></li>
				<?php if ($qbc->isAdmin()) { ?>
					<li><a href="export.php">Export</a></li>
				<?php } ?>
			</ul>
		</div>
		<div class="pull-right">
			<span class="small text-muted">
			Copyright <?php echo date("Y"); ?> University of Maryland<br />
			<a href="contactus.php">Contact us</a> with comments, questions and feedback</span>
    	</div>
	</div>
</div>
