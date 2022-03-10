<?php
$login = 0;

function adlogin($username, $password, &$dataobject){
	//**********************************
	//* Configuration goes below
	//**********************************
	$host1 = 'authldap1.abdn.ac.uk';
	$host2 = 'authldap2.abdn.ac.uk';
	$port  = 389;
	$dn    = 'OU=Migrated Users,dc=uoa,dc=abdn,dc=ac,dc=uk';
	//**************END*****************
	ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

	if(empty($password))
		return -1;

	if(!in_array($username, array(
        'mil102', // Ian Robotham
        'mil107', // Doug Bean
        'mil101', // Angela Officer
        'clt012', // Sara Preston
        's03md3', // Michael Duguid
        's04sr3', // Scott Reid
        'com265', // Jonathan Goode
        's13sb4', // Susan McBain
        's02rj6', // Rashi Jha
        's03rj6', // Rinu Jacob
        's01hs8', // Hamish Stewart
        'adr094', // Pat Rowand     - added IJR 20/08/2018
        'adr124', // Julie Mclennan - added IJR 20/08/2018
        's03mf3', // Matt Fullerton - added IJR 20/08/2018
        's03rn7', // Ros Nicolson   - added IJR 20/08/2018
        's02tb9', // Tom Balfour    - added JG  28/06/2019
        'bus078', // Pete Bartlam   - added JG  26/10/2020
		'com271', // Tracy Stasch-Goode
		's01gr9', // Gordon Renfrew
		's01hh9', // Heather Herbert
		's04mc9', // Matt Colbourne
		's08mg1', // Mehmet Giritli
    ))) {
		return -8;
    }

	//add UoA extension
	$adusername = $username.'@abdn.ac.uk';

	// open a connection to the AD - try the default host first
	if (!($ds=@ldap_connect($host1,$port))) {
		// we've failed to connect, so try the backup instead
		if (!($ds=@ldap_connect($host2,$port))) {
			return(-4);
		}
	}

	// and attempt to login (bind) to it
	if (!($status=@ldap_bind($ds,$adusername,$password))) {
		@ldap_unbind($ds);
		return(-2);
	}

	//grab some info from the server
	$filter = "(&(objectCategory=person)(sAMAccountName=$username))";
	$sr = ldap_search($ds, $dn, $filter);
	$info = ldap_get_entries($ds, $sr);
	$dataobject->name  = $info[0]['displayname'][0];
	//reformat the name
	$namecomps = explode(',', $dataobject->name);
	$dataobject->name  = trim($namecomps[1]).' '.trim($namecomps[0]);
	$dataobject->email = $info[0]['mail'][0];

	//close the connection and return the OK status
	@ldap_unbind($ds);
	return(1);
}

//************************************
//*        Error Messages
//************************************
$errors[-1] = 'You must specify a username and password.';
$errors[-2] = 'Your username and password was incorrect.';
$errors[-4] = 'Could not connect to Central Systems.';
$errors[-8] = 'You are not authorised to use this service. Please contact i.robotham@abdn.ac.uk';

//************************************
//*        Form Processing
//************************************
if(isset($_POST['cancel'])){
	//redirect to index.php
	header("Location: index.php");
} elseif(isset($_POST['login'])) {
	//strip out the nasties
	$clean['username'] = addslashes(strip_tags($_POST['username']));
	$clean['password'] = addslashes(strip_tags($_POST['password']));

	//login
	$data = new stdClass();
	$login = adlogin($clean['username'], $clean['password'], $data);

	if($login === 1){
		//login successful - setup session
		session_name('adexplorer');
		session_start();

		//build user object
		$user = new stdClass();
		$user->username = $clean['username'];
		$user->login = time();
		$user->name = $data->name;
		$user->email = $data->email;

		//serialize and store
		$_SESSION['user'] = serialize($user);

		//redirect to the requested page
		$return = htmlentities(base64_decode($_SESSION['returnto']));
		if(empty($return))
			$return = 'index.php';
		header("Location: ".$return);
	}
}
?>

<?php include 'includes/header-nomenu.inc.php'; ?>

<div id="formcontainer">
	<?php
		if($login < 0){
			echo '<p class="error-login">'.$errors[$login].'</p>';
		}
	?>
	<form action="login.php" method="post" id="loginform">
		<div class="label">
			<label for="username">University Username</label>
		</div>
		<div class="element">
			<input type="text" id="username" name="username" />
		</div>

		<div class="label">
			<label for="password">Password</label>
		</div>
		<div class="element">
			<input type="password" id="password" name="password" />
		</div>

		<div class="label">
			&nbsp;
		</div>
		<div class="element">
			<input type="submit" id="login" name="login" value="Login"/>
		</div>
	</form>
</div>

<?php include 'includes/footer.inc.php'; ?>