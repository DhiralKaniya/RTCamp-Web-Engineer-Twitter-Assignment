<?php
/**
 * server configuration
 *
 * increase maximum execution time of the server
 */
ini_set('max_execution_time', 1000);
/**
*
* Increase memory limit of the server
*/
ini_set('memory_limit', '1024M');
/**
*  include requre file
*  including tweeter oauth file
*/
require_once('lib/twitter-login/OAuth.php');
require_once('lib/twitter-login/twitteroauth.php');
/**
 * Define global variable
 * CONSUMER_KEY , CONSUMER_SECRET and OAUTH_CALLBACK specify the application..!!
 */
define('CONSUMER_KEY', 'Bs0ypdWmhnEQ7CwA13y2vKn1U');
define('CONSUMER_SECRET', 'MSGrH6vZqnPYB3OCQwj0W0ANql9JTt3Lx0NHPWEU9prGNbuIBK');
define('OAUTH_CALLBACK', 'https://rtdemo.000webhostapp.com//index.php');

#session start
/**
 * session start
 */
session_start();
/**
 * This class is basically made for the purpose of Connection of database and store-fetch data into the database
 * @category PHP
 * @author Dhiral Kaniya
 */
class DBConnection
{
    /**
     * Database conneciton object
     * @var mysqli connection object
     */
    private $Connection;
    /**
     * Database host name
     * @var String
     */
    private $hostname;
    /**
     *Database username
     * @var String
     */
    private $username;
    /**
     * Database password
     * @var String
     */
    private $password;
    /*
     * Database name
     * @var String
     */
    private $dbname;
    /**
     * Initialize consturctor of the database connectivity class
     * Also Initialize private member of the class
     * With parameter constructor take default value as system is configured
     */
    public function __construct()
    {
        $this->hostname = 'localhost';
        $this->username = 'id2325182_root';
        $this->password = 'funny@143';
        $this->dbname = 'id2325182_rtdemo';
        $this->Connection = mysqli_connect($this->hostname, $this->username, $this->password, $this->dbname);
    }
    /**
     * Insert Followers
     * @param Array @Followers
     * @param String @sc_name
     */
    public function insertFollower($follower,$sc_name)
    {
        $index = 0;
        $insert_query = "INSERT INTO tbl_follower (sc_name,screen_name) 
                  VALUES('".$sc_name."', '".$follower."')";
        $res = mysqli_query($this->Connection,$insert_query) or die(mysqli_error());
    }
    /*
     * Removew Oldest followers of the current user
     * @param String $screen_name
     */
    public function removeFollowers($screen_name)
    {
        $remove_query = "DELETE FROM tbl_follower WHERE sc_name = '".$screen_name."'";
        $res = mysqli_query($this->Connection, $remove_query) or die(mysqli_error());
    }
    /*
     * Fetch all followers of current user
     * @param String @screen_name
     * @return Array @Followers
     */
    public function searchFollowers($screen_name)
    {
        $seach_followers = "SELECT * FROM tbl_follower WHERE sc_name = '".$screen_name."'";
        $result = mysqli_query($this->Connection, $seach_followers);
        $followers = array();
        $index =0;
        while ($row = mysqli_fetch_row($result)) {
            $followers[$index]['name'] = $row[1];
            $index++;
        }
        return $followers;
    }
    /*
     * Fetch 10 followers of current user
     * @param String @screen_name
     * @return Array @Followers
     */
    public function search10Followers($screen_name)
    {
        $seach_followers = "SELECT * FROM tbl_follower WHERE sc_name = '".$screen_name."' LIMIT 10";
        $result = mysqli_query($this->Connection, $seach_followers);
        $followers = array();
        $index =0;
        while ($row = mysqli_fetch_row($result)) {
            $followers[$index]['name'] = $row[1];
            $index++;
        }
        return $followers;
    }
    /*
     * Fetch all followers of current user
     * @param String @screen_name
     * @param String @folllwer_name
     * @return Array @Followers
     */
    public function searchFollowersWithName($screen_name, $follower_name)
    {
        $seach_followers = "SELECT * FROM tbl_follower WHERE sc_name = '".$screen_name."'";
        $result = mysqli_query($this->Connection, $seach_followers);
        $followers = array();
        $index =0;
        while ($row = mysqli_fetch_row($result)) {
            if (strpos( $row[1], $follower_name)!==false){
                $followers[$index]['name'] =  $row[1];
                $index++;
            }
        }
        return $followers;
    }
    /**
     * Insert Tweets into DB
     * @param String @screen_name = identify the user
     * @Param String @id = tweet id
     * @param String @text = Tweet text
     */
    public function insertTweet($screen_name, $id,$text)
    {
        $insert_tweet = "INSERT INTO tbl_tweet(sc_name,id,text) VALUES ('".$screen_name."','".$id."','".$text."')";
        mysqli_query($this->Connection, $insert_tweet);

    }
    /**
     * Remove All tweets of current login users
     * @param String @screen_name = identify the user
     */
    public function removeTweet($screen_name)
    {
        $remove_tweet = "DELETE FROM tbl_tweet WHERE sc_name = '".$screen_name."'";
        mysqli_query($this->Connection, $remove_tweet) or die(mysqli_error());
    }
    /*
     * Search all tweets of the particular user
     * @param String @screen_name = identify the user
     * @return Array @tweets
     */
    public function searchTweet($screen_name)
    {
        $search_tweet = "SELECT * FROM tbl_tweet WHERE sc_name = '".$screen_name."'";
        $result = mysqli_query($this->Connection, $search_tweet);
        $tweets = array();
        $index = 0;
        while ($row = mysqli_fetch_row($result)) {
            $tweets[$index]['id'] = $row[1];
            $tweets[$index]['tweet'] = $row[2];
            $index++;
        }
        return $tweets;
    }
}
