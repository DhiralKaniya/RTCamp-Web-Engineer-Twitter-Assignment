<?php 
#include requre file
/*
*
* function.php specify all functionality's defination which is implemented..
*/
require('Functions.php');


#Object of functionality class
/*
*
* 
*/
$function = new Functionality();

#logout
/*
*
* Identify the logout and redirect to the logout function.
*/
if(isset($_GET['logout'])){
	$function->logout();
}

#logint 
/*
*
* Identify login funcationality and refirect logint function
*/
if(!isset($_SESSION['data']) && !isset($_GET['oauth_token'])) {
	$function->logintURL();
}


#CALLBACk
/*
*
* Handle call back function from the twitter and redirect to callBack Handler
*/
if(isset($_GET['oauth_token'])){
	echo $_SESSION['request_token'];
	echo $_SESSION['request_token_secret'];
	$request_token = $_REQUEST['oauth_verifier'];
	$function->handleCallback($request_token);
}

#USER TWEET
/*
*
*	identify user tweet and redirect to the user tweet function
*/
if(isset($_REQUEST['user-tweet'])){
	//Fetching current tweets of login user
	if(isset($_REQUEST['follower'])){
		$response = $function->userTweet($_REQUEST['follower']);
	}else{
		$response = $function->userTweet(null);
	}
	echo $response;
}

#USER FOLLOWER
/*
*
*	identify user follower request and redirect to the user follower function
*/
if(isset($_REQUEST['user-followers'])){
	echo $function->userFollower();
}
#USER TWEET
/*
*
*	identify user search request and redirect to the user tweet function
*/
if(isset($_REQUEST['search-followers'])){
	echo $function->searchFollower($_REQUEST['follower']);
}