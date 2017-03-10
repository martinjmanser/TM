<?php
	// persist share link query string
    if (isset($_SESSION['query_string'])) {
    	$query_string = $_SESSION['query_string'];
	} else {
		$query_string = '';
	}
?>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <a class="navbar-brand" href="index.php<?=$query_string?>"><b>TribeMind</b></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <?

        	if (isset($_SESSION['name'])) { ?>
        			<li><a href="challenge.php">Receive</a></li>
			        <li><a href="viewReflections.php">View</a></li>
			        <li><a href="submit.php">Submit</a></li>
			        <li class="key-points"><span class="key-points-text"> Key Points: <?php echo $_SESSION['keypoints']; ?> </span></li>
			        <li class="logged-in"><span class="logged-in-text"> <?php echo $_SESSION['name']; ?> | <a href="logout.php">Logout</a></span></li>
			<? } else { ?>
        		<li><a href="login.php<?=$query_string?>">Log in</a></li>
        		<li><a href="register.php<?=$query_string?>">Register</a></li>
        	<? } ?>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>