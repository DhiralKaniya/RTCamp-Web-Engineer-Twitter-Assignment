<?php
/** Include requre file
 *
 *
 * function.php specify all functionality's defination which is implemented..
 */
require('Functions.php');
$function = new Functionality();
/**
 * Identify @logout request
 *
 * @param $_GET[logout]
 * Redirection at index.php
 */
if (isset($_GET['logout'])) {
    $function->logout();
}
/**
 * Identify logint request
 *
 * @param $_SESSION[data]
 * @param $_GET[oauth_toke]
 */
if (!isset($_SESSION['data']) && !isset($_GET['oauth_token'])) {
    $function->logintURL();
}
/**
 * Handle call back from the twitter
 *
 * @param $_GET [oauth_token]
 */
if (isset($_GET['oauth_token'])) {
    echo $_SESSION['request_token'];
    echo $_SESSION['request_token_secret'];
    $request_token = $_REQUEST['oauth_verifier'];
    $function->handleCallback($request_token);
}


/**
 * Identify user tweet and redirect to the user tweet function
 * indetify logout request
 *
 * @param $_GET[user-tweet]
 * @param $_GET[follower]
 * @return $response tweet list in json format
 */
if (isset($_REQUEST['user-tweet'])) {
    //Fetching current tweets of login user
    if (isset($_REQUEST['follower'])) {
        $response = $function->userTweet($_REQUEST['follower']);
    } else {
        $response = $function->userTweet(null);
    }
    echo $response;
}

/**
 * Identify user follower request and redirect to the user follower function
 *
 * @param $_GET user-followers
 * @return $response follower list in json format
 */
if (isset($_REQUEST['user-followers'])) {
    echo $function->userFollower();
}

/**
 * Identify user search request and redirect to the user tweet function
 *
 * @param $_GET search-followers
 * @return $response follower list in json format
 */
if (isset($_REQUEST['search-followers'])) {
    echo $function->searchFollower($_REQUEST['follower']);
}
/**
 * Handle json-format tweet download request
 * @return 'tweet in json formt'
 */
if (isset($_REQUEST['json-format'])) {
    header('Content-Disposition:attachment;filename=mytweets.json');
    header('content-type:application/json');
    echo json_encode($function->getTweets());
}
/**
 * Handle csv-format tweet download request
 * @return 'all tweet in csv formt'
 */
if (isset($_REQUEST['csv-format'])) {
    $f = fopen('php://memory', 'w');
    $tweets = $function->getTweets();
    $delimiter=",";
    fputcsv($f, array("id", "createdAt", "text", "name", "screen_name", "profile_image"), $delimiter);
    foreach ($tweets as $tweet) {
        fputcsv($f, $tweet, $delimiter);
    }
    fseek($f, 0);
    header('Content-Type:application/csv');
    header('Content-Disposition:attachment;filename=mytweets.csv');
    fpassthru($f);
}
/**
 * Handle xls-format tweet download request
 * @return 'all tweet in xls formt'
 */
if (isset($_REQUEST['xls-format'])) {
    $tweets = $function->getTweets();
    $mycolumn = array("id","createdAt","text","name","screen_name","profile_image");
    header('content-type:application/vnd.ms-excel');
    header('Content-Disposition:attachment;filename=mytweet.xls');
    echo implode("\t", array_values($mycolumn))."\r\n";
    foreach ($tweets as $tweet) {
        echo implode("\t", array_values($tweet))."\r\n";
    }
}

/**
 * Handle google spreadsheet tweet export request
 * Redirect at google
 */
if (isset($_REQUEST['google-spreadsheet'])) {
    $_SESSION['user-tweets'] = $function->getTweets();
    header('location:lib\google-drive-api/index.php');
}
