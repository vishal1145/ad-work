<?php 
	require_once 'includes/adlib.php';

	$sName = addslashes(strip_tags($_POST['name'])); 
	if(empty($sName)){
		header("Location: searchbyname.php");
		exit;
	}

	//get the user info
	$users = ad_get_users_list($sName);
	
?>
<?php include 'includes/header.inc.php'; ?>

<h1>Search by Name: <?php echo $sName; ?></h1>

<table>
	<tr>
		<th style="text-align: left">Name</th>
		<th style="text-align: left">Username</th>
		<th style="text-align: left">Email</th>
		<th style="text-align: center">More Details</th>
	</tr>
<?php
	foreach($users as $ouser){
		if(!empty($ouser->username)){
			echo '<tr>';
			echo '    <td>'.$ouser->name.'</td>';
			echo '    <td>'.$ouser->username.'</td>';
			echo '    <td>'.$ouser->email.'</td>';
			echo '    <td style="text-align: center"><a href="userinfo.php?username='.$ouser->username.'">More</a></td>';
			echo '</tr>';
		}
	}
?>
</table>
<?php include 'includes/footer.inc.php'; ?>