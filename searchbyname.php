<?php include 'includes/header.inc.php'; ?>

<h1>Search by Name</h1>

<form action="list.php" method="post">
	<label for="name">Name</label>
	<input id="name" name="name" type="text" />
	<input id="search" name="search" type="submit" value="Search" />
</form>

<?php include 'includes/footer.inc.php'; ?>