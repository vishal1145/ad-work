<?php
/**********************************************************************************************
 * lib.php - a library of PHP function to interact with LDAP services to lookup user
 * information
 *
 * @author     Ian J Robotham (i.robotham@abdn.ac.uk)
 * @version    2010083000
 *********************************************************************************************/

define("AD_HOST_1", "authldap1.abdn.ac.uk");
define("AD_HOST_2", "authldap2.abdn.ac.uk");
define("AD_PORT", "389");
define("AD_DN", "OU=Migrated Users,dc=uoa,dc=abdn,dc=ac,dc=uk");

define("AD_BIND_USER", "");
define("AD_BIND_PASSWORD", "");

/**
 * ad_connect binds to an Active Directory server
 * @return  resource  an LDAP connection
 */
 function ad_connect(){
	ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

	// open a connection to the AD - try the default host first
	if (!($ds=@ldap_connect(AD_HOST_1,AD_PORT))) {
		// we've failed to connect, so try the backup instead
		if (!($ds=@ldap_connect(AD_HOST_2,AD_PORT))) {
			die("Couldn't connect to any AD Server");
		}
	}

	// and attempt to login (bind) to it
	if (!($status=@ldap_bind($ds,AD_BIND_USER,AD_BIND_PASSWORD))) {
		@ldap_unbind($ds);
		die("Bind Username/Password combination invalid");
	}

	return $ds;
 }

/**
 * ad_disconnect cleans up connections to Active Directory
 * @param  resource  $connection   an active connection
 */
 function ad_disconnect($connection){
	@ldap_unbind($connection);
 }

/**
 * ad_get_dump queries the active directory servers and gets a dump of all possible information
 * @param   string   $username   the username to search for
 * @return  array   returns an array from the server populated with user information
 **/
function ad_get_dump($username){
	//make connection to server
	$ds = ad_connect();

	//grab some info from the server
	$filter = "(&(objectCategory=person)(sAMAccountName=$username))";
	$sr = ldap_search($ds, AD_DN, $filter);
	$info = ldap_get_entries($ds, $sr);

	return $info;
}


/**
 * ad_get_info queries the active directory servers lookup for user information
 * @param   string   $username   the username to search for
 * @return  object   returns a stdClass populated with user information
 **/
function ad_get_info($username){
	$data = new stdClass();

	//make connection to server
	$ds = ad_connect();

	//grab some info from the server
	$filter = "(&(objectCategory=person)(sAMAccountName=$username))";
	$sr = ldap_search($ds, AD_DN, $filter);
	$info = ldap_get_entries($ds, $sr);

	//get title
	$data->title = $info[0]['personaltitle'][0];
	//get name
	$data->name  = $info[0]['displayname'][0];
	//reformat the name
	$namecomps = explode(',', $data->name);
	$data->name  = trim($namecomps[1]).' '.trim($namecomps[0]);
	

	$data->role = $info[0]['employeetype'][0];

	//========================================================
	//                 Contact Information
	//========================================================
	//get email
	$data->email = $info[0]['mail'][0];

	//get telephone
	$data->telephone = $info[0]['telephonenumber'][0];

	//========================================================
	//                Department Information
	//========================================================
	$data->department = $info[0]['title'][0];
	$data->section = $info[0]['physicaldeliveryofficename'][0];
	$data->company = $info[0]['company'][0];

	//========================================================
	//                 Account Information
	//========================================================
	$data->accountcreated = $info[0]['whencreated'][0];
	$data->accountexpires = $info[0]['accountexpires'][0];
	$data->lockouttime = $info[0]['lockouttime'][0];

	$data->badpwdcount = $info[0]['badpwdcount'][0];
	$data->badpwdtimestamp = $info[0]['badpasswordtime'][0];

	$data->lastlogoff = $info[0]['lastlogoff'][0];
	$data->lastlogon = $info[0]['lastlogon'][0];
	$data->lastlogontimestamp = $info[0]['lastlogontimestamp'][0];
	$data->logoncount = $info[0]['logoncount'][0];

	$data->pwdlastset = $info[0]['pwdlastset'][0];

	$data->useraccountcontrol = $info[0]['useraccountcontrol'][0];

	$data->homedirectory = $info[0]['homedirectory'][0];
	$data->unixhomedirectory = $info[0]['unixhomedirectory'][0];

	//close the connection
	ad_disconnect($ds);

	return $data;
}

/**
 * ad_get_users_list gets a list of users who match a partial name
 * @param   string   $name   the name to search for
 * @return  array    an array of objects containing usernames, names & email addresses
 **/
function ad_get_users_list($name){
	$data = array();

	//make connection to server
	$ds = ad_connect();

	//grab some info from the server
	$filter = "(&(objectCategory=person)(displayname=*$name*))";
	$sr = ldap_search($ds, AD_DN, $filter);
	$users = ldap_get_entries($ds, $sr);

	//iterate over the users
	foreach($users as $ouser){
		$dataobject = new stdClass();
		$dataobject->name = $ouser['displayname'][0];
		$dataobject->username = $ouser['name'][0];
		$dataobject->email = $ouser['mail'][0];

		$data[] = $dataobject;
	} 

	//close the connection
	ad_disconnect($ds);

	return $data;
}

/**
 * adtime_to_unixtime converts an Active Directory timestamp to a unix timestamp
 * @param   int  $time   the AD timestamp
 * @return  int  the unix time
 **/ 
function adtime_to_unixtime($time, $epochoffset=11644470000){
        $secsAfterADEpoch = $time / (10000000); // seconds since jan 1st 1601
	 $ADToUnixConvertor = $epochoffset;
        //$ADToUnixConvertor=((1970-1601) * 365.2421897) * 86400; // unix epoch - AD epoch * number of tropical days * seconds in a day
        return intval($secsAfterADEpoch-$ADToUnixConvertor); // unix Timestamp version of AD timestamp
}


/**
 * ad_account_status checks to see what the current account status is
 * @param   int     $status   the User Account Control value
 * @return  string  the current status
 **/
function ad_account_status($status){
	if(($status & 0x0002) > 0){
		return 'Disabled ('.$status.')';
	} else if(($status & 0x0010) > 0){
		return 'Locked Out ('.$status.')';
	} else if(($status & 0x800000) > 0) {
		return 'Password Expired ('.$status.')';
	} else {
		return 'Active ('.$status.')';
	}
}
?>