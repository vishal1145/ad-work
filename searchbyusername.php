<?php include 'includes/header.inc.php'; ?>

<h1>Search by Username</h1>

<form action="userinfo.php" method="post">
	<label for="username">Username</label>
	<input id="username" name="username" type="text" />
	<input id="search" name="search" type="submit" value="Search" />
</form>

<?php include 'includes/footer.inc.php'; ?>