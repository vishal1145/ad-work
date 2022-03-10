<?php 
	require_once 'includes/adlib.php';

	$sUsername = addslashes(strip_tags($_REQUEST['username'])); 
	if(empty($sUsername)){
		header("Location: searchbyusername.php");
		exit;
	}

	//get the user info
	$userinfo = ad_get_info($sUsername);
	
?>
<?php include 'includes/header.inc.php'; ?>

<h1>Search by Username: <?php echo $sUsername; ?></h1>

<div id="tabs">
	<ul>
		<li><a href="#contact">Contact Information</a></li>
		<li><a href="#department">Department Information</a></li>
		<li><a href="#account">Account Information</a></li>
		<li><a href="#dump">Full AD Dump</a></li>
	</ul>

	<div id="contact">
		<table class="tabtable">
			<tr>
				<th>Name</th>
				<td><?php echo $userinfo->title . ' ' . $userinfo->name; ?></td>
			</tr>
			<tr>
				<th>User type</th>
				<td><?php echo $userinfo->role; ?></td>
			</tr>
			<tr>
				<th>Username</th>
				<td><?php echo $sUsername; ?></td>
			</tr>
			<tr>
				<th>Email Address</th>
				<td><?php echo $userinfo->email; ?></td>
			</tr>
			<tr>
				<th>Telephone Number</th>
				<td><?php echo $userinfo->telephone; ?></td>
			</tr>
		</table>
	</div>

	<div id="department">
		<table class="tabtable">
			<tr>
				<th>Department</th>
				<td><?php echo $userinfo->department; ?></td>
			</tr>
			<tr>
				<th>Section</th>
				<td><?php echo $userinfo->section; ?></td>
			</tr>
			<tr>
				<th>Organisation</th>
				<td><?php echo $userinfo->company; ?></td>
			</tr>
		</table>
	</div>

	<div id="account">
		<table class="tabtable">
			<tr>
				<th>Account Status</th>
				<td><?php echo ad_account_status($userinfo->useraccountcontrol); ?></td>
				<th>&nbsp;</th>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<th>Account Created</th>
				<td><?php echo gmdate("j F Y H:i:s", process_time($userinfo->accountcreated)); ?></td>
				<th>Last Log On (UOA domain)</th>
				<td><?php echo gmdate("j F Y H:i:s", adtime_to_unixtime($userinfo->lastlogon)); ?></td>
			</tr>
			<tr>
				<th>Account Expires</th>
				<td><?php if($userinfo->accountexpires==9223372036854775807){ echo "Never"; } else { echo gmdate("j F Y H:i:s", adtime_to_unixtime($userinfo->accountexpires));} ?></td>
				<th>Logon Count</th>
				<td><?php echo $userinfo->logoncount; ?></td>
			</tr>
			<tr>
				<th>Lock out time</th>
				<td><?php echo $userinfo->lockouttime; ?></td>

			</tr>
			<tr>
				<th>Password Last Set</th>
				<td><?php echo gmdate("j F Y H:i:s", adtime_to_unixtime($userinfo->pwdlastset)); ?></td>
				<th>Bad Password</th>
				<td><?php echo gmdate("j F Y H:i:s", adtime_to_unixtime($userinfo->badpwdtimestamp)) . ' (Count: '. $userinfo->badpwdcount.')'; ?></td>
			</tr>
			<tr>
				<th>Home Directory</th>
				<td><?php echo $userinfo->homedirectory; ?></td>
				<th>UNIX Home</th>
				<td><?php echo $userinfo->unixhomedirectory	; ?></td>
			</tr>
		</table>
	</div>
	<div id="dump">
		<pre>
			<?php
				print_r(ad_get_dump($sUsername));
			?>
		</pre>
	</div>
</div>

<script type="text/javascript">
	$(function() {
		$("#tabs").tabs();
	});
</script>

<?php
	function process_time($date){
		$y = substr($date, 0, 4);
		$m = substr($date, 4, 2);
		$d = substr($date, 6, 2);
		$h = substr($date, 8, 2);
		$i = substr($date, 10, 2);
		$s = substr($date, 12, 2);

		return mktime($h,$i,$s,$m,$d,$y);
	}
?>
<?php include 'includes/footer.inc.php'; ?>