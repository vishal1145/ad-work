<?php
	//setup the session
	session_name('adexplorer');
	session_start();


    /****************************************************************
     *                   Library of Functions
     ****************************************************************/

	/**
	 * Requires that a user be logged in to continue, if they are
	 * not logged in it will redirect them to the login page after
	 * setting a returnto location which can be (optionally) specified
	 * @param  string   $returnto  an optional redirect location, by default 
	 * the page from which the function is called.
	 **/
        function require_login($returnto=''){
		//safely logged in
		$loggedin = false;
		
		//check for the user session
		if(isset($_SESSION['user'])){
			$user = unserialize($_SESSION['user']);

			//check if the time is good
			if($user->login > (time() - (1*60*60))){ //maximum of a 60 minute session login
				$loggedin = true;
			}
		}

		//check if we have logged in and act if we are *NOT* logged in
		if(!$loggedin){
			if(empty($returnto))
				$returnto = 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

			$_SESSION['returnto'] = base64_encode($returnto);
			header("Location: login.php");
			exit();
		}		
        }

	/**
	 * Gets the username the user is logged in as
	 * @return string the login name or empty
	 **/
        function get_login_username(){
		$user = false;
		
		//check for the user session
		if(isset($_SESSION['user'])){
			$user = unserialize($_SESSION['user']);

			//check if the time is good
			if($user->login > (time() - (1*60*60))){ //maximum of a 60 minute session login
				$user = $user->username;
			}
		}

		return !empty($user)?$user:'';
        }

	/**
	 * Gets the name the user is logged in as
	 * @return string the login name or empty
	 **/
        function get_login_name(){
		$user = false;
		
		//check for the user session
		if(isset($_SESSION['user'])){
			$user = unserialize($_SESSION['user']);

			//check if the time is good
			if($user->login > (time() - (1*60*60))){ //maximum of a 60 minute session login
				$user = $user->name;
			}
		}

		return !empty($user)?$user:'';
        }

	/**
	 * Gets the email address of currently logged in user
	 * @return string the email address
	 **/
        function get_login_email(){
		$email = false;
		
		//check for the user session
		if(isset($_SESSION['user'])){
			$user = unserialize($_SESSION['user']);

			//check if the time is good
			if($user->login > (time() - (1*60*60))){ //maximum of a 60 minute session login
				$email = $user->email;
			}
		}

		return !empty($email)?$email:'';
        }


	/**
	 * Request a Sci59 account or grab existing user details
	 * @param string	$username		the user's UoA username
	 * @param string	$sci59username	the allocated Sci59 username
	 * @param string	$sci59password	the allocated Sci59 password#
	 * @return boolean true if account creation is successful
	 **/
	function request_sci59_account($username, &$sci59username, &$sci59password){
		$accountfile    = '/home/web-b/wmm087/public_html/developments/medicalcareers/sci59/accounts.csv';
		$allocationfile = '/home/web-b/wmm087/public_html/developments/medicalcareers/sci59/allocated.csv';

		$sci59username = 'Error';
		$sci59password = 'Error';

		//look up the current allocation
		$allocations = file($allocationfile);		
		foreach($allocations as $allocation){
			//tokenize
			$allocationtokens = explode(',', $allocation); 

			//check for an existing allocation, return details if so
			if(trim($allocationtokens[0]) == $username){
				$sci59username = trim($allocationtokens[1]);
				$sci59password = str_replace("\n", "", trim($allocationtokens[2]));
				return true;
			}
		}

		//no previous allocation, so retrieve unused accounts
		$accounts = file($accountfile);
		if(count($accounts) > 0){
			//allocate account
			$newallocation = $username.','.str_replace("\n", "", trim($accounts[0])).','.time()."\n";
			file_put_contents($allocationfile,$newallocation,FILE_APPEND);

			$accounttokens = explode(',', $accounts[0]);
			$sci59username = trim($accounttokens[0]);
			$sci59password = str_replace("\n", "", trim($accounttokens[1]));

			//remove from allocatable accounts
			unset($accounts[0]);
			$accountsout = '';
			foreach($accounts as $account){
				$accountsout .= $account."\n";	
			}
			file_put_contents($accountfile,$accountsout);				

			return true;
		}

		return false; //couldn't allocate an account
	}

    /****************************************************************/
?>