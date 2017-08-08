<?php
   require_once('model.php');
  /**
  * #This is functionality class which include all the functionality of the project.
  * @category PHP
  * @author "Dhiral Kaniya"
  *
  */
class Functionality {
    /**
    * Handle logout functionality
    *
    */
    public function logout()
    {
        session_unset();
        $home_path = 'index.php';
        header('Location:'.filter_var($home_path, FILTER_SANITIZE_URL));
    }
    /**
     *Handle login url request
     *
     * set $_SESSION['login url']
     * Redirect at login url
     */
    public function logintURL()
    {
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
        $request_token = $connection->getRequestToken(OAUTH_CALLBACK);
        if ($request_token) {
            $token = $request_token['oauth_token'];
            $_SESSION['request_token'] = $token ;
            $_SESSION['request_token_secret'] = $request_token['oauth_token_secret'];
            $_SESSION['login_url'] = $connection->getAuthorizeURL($token);
            header('Location'.filter_var($_SESSION['login_url'], FILTER_SANITIZE_URL));
        }
    }
    /**
     * Handle call back from the twitter
     *
     * @param = @request_token :- which provided by twitter once user authorized the
     * application
     * Redirection at the call back page after call back is success
     */
    public function handleCallback($request_token)
    {
        $connection = new TwitterOAuth(
            CONSUMER_KEY,
            CONSUMER_SECRET,
            $_SESSION['request_token'],
            $_SESSION['request_token_secret']
        );
        $access_token = $connection->getAccessToken($request_token);
        $_SESSION['access_token'] = $access_token;
        if ($access_token) {
            $connection = new TwitterOAuth(
                CONSUMER_KEY,
                CONSUMER_SECRET,
                $access_token['oauth_token'],
                $access_token['oauth_token_secret']
            );
            $params =array('include_entities'=>'false');
            $data = $connection->get('account/verify_credentials', $params);
            if ($data) {
                $_SESSION['data']=$data;
                $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
                header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
            }
        }
    }
    /**
     * @param = @follower = its screen_name of the follower
     * If $follower == null then it return current login users tweet
     * @return $response = 'user tweet with status in json format'
     */
    public function userTweet($follower)
    {
        $access_token = $_SESSION['access_token'];
        $connection = new TwitterOAuth(
            CONSUMER_KEY,
            CONSUMER_SECRET,
            $access_token['oauth_token'],
            $access_token['oauth_token_secret']
        );
        if ($follower!=null) {
            $screen_name = $_REQUEST['follower'];
            $params = array('include_entities' => false,'count'=>10,'screen_name'=>$screen_name);
        } else {
            $params =array('include_entities'=>'false','count'=>10);
        }

        $tweets = $connection->get('statuses/user_timeline', $params);
        $tweet_result = array();
        if (isset($tweets->errors[0]->code)) {
            $tweet_result[0]['text'] = "Internal Error occure";
            $tweet_result[0]['images'] = '';
        } else {
            foreach ($tweets as $key => $tweet) {
                $text = $tweet->text;
                $tweet_result[$key]['text']=preg_replace(
                    "~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~",
                    "<a target='_blank' href=\"\\0\">\\0</a>",
                    $text
                );
                if (isset($tweet->extended_entities->media[0]->media_url_https)) {
                    $tweet_result[$key]['images']= $tweet->extended_entities->media[0]->media_url_https;
                } else {
                    $tweet_result[$key]['images']='';
                }
            }
        }
        $response =  json_encode(array('status'=>true,'data'=>$tweet_result));
        return $response;
    }
    /**
     * User follower:- get current logged in user follower
     * return #response = 'all followers of current logged user in json format'
     *
     */
    public function userFollower()
    {
        $access_token = $_SESSION['access_token'];
        $connection = new TwitterOAuth(
            CONSUMER_KEY,
            CONSUMER_SECRET,
            $access_token['oauth_token'],
            $access_token['oauth_token_secret']
        );
        $data = $_SESSION['data'];
        $profiles = array();
        $sc_name = $data->screen_name;
        $cursor = -1;
        $index = 0;
        while ($cursor != 0) {
            $ids = $connection->get("followers/ids", array("screen_name" => $sc_name, "cursor" => $cursor));
            $cursor = $ids->next_cursor;
            $ids_arrays = array_chunk($ids->ids, 50);
            foreach ($ids_arrays as $implode) {
                $user_ids=implode(',', $implode);
                $results = $connection->get("users/lookup", array("user_id" => $user_ids));
                foreach ($results as $profile) {
                    $profiles[$index]['id'] = $profile->id;
                    $profiles[$index]['name'] = $profile->screen_name;
                    $profiles[$index]['image'] = $profile->profile_image_url_https;
                    $index++;
                }
            }
        }
        $_SESSION['followers'] = $profiles;
        $profile_home = array();
        $index = 0;
        while ($index < 10) {
            $profile_home[$index] = $profiles[$index];
            $index++;
        }
        $response = json_encode(array('status'=>true,'data'=>$profile_home));
        return $response;
    }

    /**
     * search follower:- search the follower from the current follower list,call when user
     * type in search box
     * @param  @follower :- specify the user type follower name
     * @return string
     */
    public function searchFollower($follower)
    {
        $ftext = $follower;
        $followers = $_SESSION['followers'];
        $search_follower = array();
        $index = 0;
        foreach ($followers as $follower) {
            if (strpos($follower['name'], $ftext)!==false) {
                $search_follower[$index] = $follower;
                $index++;
            }
        }
        $response = json_encode(array('status'=>true,'data'=>$search_follower));
        return $response;
    }

    /**
     * get tweets of current login user
     * @return array @$tweet_result array
     */
    public function getTweets()
    {
        $access_token = $_SESSION['access_token'];
        $connection = new TwitterOAuth(
            CONSUMER_KEY,
            CONSUMER_SECRET,
            $access_token['oauth_token'],
            $access_token['oauth_token_secret']
        );
        $data = $_SESSION['data'];
        $count = 10000;
        $params = array("screen_name"=>$data->screen_name,"count"=>$count);
        $tweets = $connection->get('statuses/home_timeline', $params);
        $tweet_result = array();
        $index = 0;
        foreach ($tweets as $tweet) {
            $tweet_result[$index]['id'] = $tweet->id_str;
            $tweet_result[$index]['createdAt'] = $tweet->created_at;
            $tweet_result[$index]['text'] = $tweet->text;
            $tweet_result[$index]['name'] = $tweet->user->name;
            $tweet_result[$index]['screen_name'] = $tweet->user->screen_name;
            $tweet_result[$index]['profileImageurl'] = $tweet->user->profile_image_url;
            $index++;
        }
        return $tweet_result;
    }
}
