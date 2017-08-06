<?php 
	#server configuration 
	/*
	*
	* increase maximum execution time of the server
	*/
	ini_set('max_execution_time', 600);
	/*
	*
	* increase memory limit of the server
	*/
	ini_set('memory_limit', '1024M');
	#include requre file
	/*
	*
	* including tweeter oauth file
	*/
	require_once('lib/twitter-login/OAuth.php');
	require_once('lib/twitter-login/twitteroauth.php');

	#define global variable
	/*
	*
	* CONSUMER_KEY , CONSUMER_SECRET and OAUTH_CALLBACK specify the application..!!
	*/
	define('CONSUMER_KEY', 'Bs0ypdWmhnEQ7CwA13y2vKn1U');
	define('CONSUMER_SECRET', 'MSGrH6vZqnPYB3OCQwj0W0ANql9JTt3Lx0NHPWEU9prGNbuIBK');
	define('OAUTH_CALLBACK', 'https://rtdemo.000webhostapp.com//index.php');

	#session start
	/*
	*
	* session start
	*/
	session_start();