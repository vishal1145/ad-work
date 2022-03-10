<?php
require_once('includes/lib.php');
require_login('index.php');

echo '<?xml version="1.0" encoding="iso-8859-1"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php echo (empty($pagetitle)?'AD Explorer':$pagetitle); ?></title>
		<link rel="stylesheet" type="text/css" href="includes/css/site.css" />
		<link type="text/css" href="includes/css/custom-theme/jquery-ui-1.8.4.custom.css" rel="stylesheet" />	
		<script type="text/javascript" src="includes/js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="includes/js/jquery-ui-1.8.4.custom.min.js"></script>
		
	</head>
	
	<body>
		<div id="content">
			<div id="header">
				<div id="logo">
					<?php
						$name = get_login_name();
						if(!empty($name)){
							echo '<p id="loggedinline">Welcome, '.$name.'! <a href="logout.php">Logout</a></p>';
						} else {
							echo '&nbsp;';
						}
					 ?>					
				</div>
				<div class="clearfix"></div>
				<div id="menu">
					<ul>
						<li><a href="index.php" title=""><img src="includes/images/icons/16x16/home.png" alt="Home" style="border: none;"/> Home</a></li>
					</ul>
				</div>
				<div id="searchform">				
					<form action="list.php" method="post">
						<label for="n">Search by Name</label>
						<input id="n" name="name" type="text" />
						<input id="btnSearch" name="btnSearch" type="submit" value=" "  />
					</form>

					<form action="userinfo.php" method="post">
						<label for="un">Search by Username</label>
						<input id="un" name="username" type="text" />
						<input id="btnSearch" name="btnSearch" type="submit" value=" "  />
					</form>
				</div>				
			</div>
			
			<div class="clearfix"></div>
			
			<div id="body">