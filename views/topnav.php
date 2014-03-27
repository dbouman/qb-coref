<div class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
        	<div class="navbar-header">
          		<a class="navbar-brand" href="index.php">QB-Coref</a>
          	</div>
          	<div class="collapse navbar-collapse pull-left">
	            <ul class="nav navbar-nav">
	              <li<?php if ($active_page == "questions") { ?> class="active"<?php } ?>><a href="index.php">Questions</a></li>
	              <li<?php if ($active_page == "leaderboard") { ?> class="active"<?php } ?>><a href="leaderboard.php">Leaderboard</a></li>
	              <li><span class="navbar-text">Your Points: <span id="points"><?php print $qbc->getPoints(); ?></span></span></li>
	            </ul>
          	</div>
          	<div class="collapse navbar-collapse pull-right">
	            <ul class="nav navbar-nav">
	              <li><span class="navbar-text">Welcome <?php print $_SESSION['username']; ?></span></li>
	              <li<?php if ($active_page == "help") { ?> class="active"<?php } ?>><a href="help.php">Getting Started</a>
	              <li<?php if ($active_page == "myaccount") { ?> class="active"<?php } ?>><a href="myaccount.php">My Account</a></li>
	              <li><a href="login.php">Logout</a></li>
	            </ul>
          	</div><!--/.nav-collapse -->
        </div>
    </div>