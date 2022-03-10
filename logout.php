<?php 
	require_once 'includes/lib.php';

	unset($_SESSION['user']);
	session_destroy();

	$pagetitle = 'Log Out';
	include 'includes/header.inc.php'; 
?>

<h1>Log Out</h1>
<p>You have now been logged out.</p>

<?php include 'includes/footer.inc.php'; ?>