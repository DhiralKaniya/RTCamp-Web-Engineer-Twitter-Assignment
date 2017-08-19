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
        unset($_SESSION['data']);
        unset($_SESSION['login_url']);
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
                $sc_name = $data->screen_name;
                $cursor = -1;
                $DBObject = new DBConnection();
                $DBObject->removeFollowers($data->screen_name);
                while ($cursor != 0) {
                    $ids = $connection->get("followers/ids", array("screen_name" => $sc_name, "cursor" => $cursor));
                    $cursor = $ids->next_cursor;
                    $ids_arrays = array_chunk($ids->ids, 50);
                    foreach ($ids_arrays as $implode) {
                        $user_ids=implode(',', $implode);
                        $results = $connection->get("users/lookup", array("user_id" => $user_ids));
                        foreach ($results as $profile) {
                            $DBObject->insertFollower($profile->screen_name, $data->screen_name);
                        }
                    }
                }
                //set the current user data in the session
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
            $tweets = $connection->get('statuses/user_timeline', $params);
        } else {
            $params =array('include_entities'=>'false','count'=>10);
            $tweets = $connection->get('statuses/home_timeline', $params);
        }
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
        $data = $_SESSION['data'];
        $DBObject = new DBConnection();
        $profiles = $DBObject->search10Followers($data->screen_name);
        $response = json_encode(array('status'=>true,'data'=>$profiles));
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
        $follower_name = $follower;
        $data = $_SESSION['data'];
        $DBObject = new DBConnection();
        $followers =$DBObject->searchFollowersWithName($data->screen_name, $follower_name);
        $response = json_encode(array('status'=>true,'data'=>$followers));
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
        $total_tweets = $data->statuses_count;
        $screen_name = $data->screen_name;
        $index = 0;

        $DBObject = new DBConnection();
        $DBObject->removeTweet($screen_name);
        $prev_id = 0;
        while ($index<$total_tweets) {
            if ($index == 0) {
                $params = array("screen_name" => $screen_name, "count"=>200,
                    "include_rts"=>true,'exclude_replies'=>false);
            } else {
                $params = array("screen_name" => $screen_name, "count" => 200,
                    "max_id" => $prev_id, "include_rts"=>true,'exclude_replies'=>false);
            }

            $tweets = $connection->get("statuses/user_timeline", $params);
            if ($tweets == null) {
                break;
            }

            foreach ($tweets as $tweet) {
                $DBObject->insertTweet($data->screen_name, $tweet->id_str, $tweet->text);
                $index +=1;
                $prev_id = $tweet->id_str;
            }
        }
        $tw = $DBObject->searchTweet($data->screen_name);
        return $tw;
    }

    /**
     * @param $screen_name
     * @return array
     */
    public function searchUsers($screen_name)
    {
        $access_token = $_SESSION['access_token'];
        $connection = new TwitterOAuth(
            CONSUMER_KEY,
            CONSUMER_SECRET,
            $access_token['oauth_token'],
            $access_token['oauth_token_secret']
        );
        $params = array("screen_name"=>$screen_name);
        $user_response = $connection->get("users/lookup", $params);

        $user = array();
        $index = 0;
        foreach ($user_response as $u){
            $user[$index]['id'] = $u->id_str;
            $user[$index]['screen_name'] = $u->screen_name;
            $user[$index]['name'] = $u->name;
            $user[$index]['image'] = $u->profile_image_url_https;
            $user[$index]['location'] = $u->location;
            $user[$index]['followers'] = $u->followers_count;
            $user[$index]['tweets'] = $u->statuses_count;
            $user[$index]['background'] = $u->profile_background_image_url_https;
            $index++;
        }
        return $user;
    }

    /**
     * @param $screen_name
     * @return array
     */
    public function generatePDFTweet($screen_name)
    {
        $access_token = $_SESSION['access_token'];
        $connection = new TwitterOAuth(
            CONSUMER_KEY,
            CONSUMER_SECRET,
            $access_token['oauth_token'],
            $access_token['oauth_token_secret']
        );
        $params = array("screen_name"=>$screen_name);
        $user_response = $connection->get("users/lookup", $params);
        $total = $user_response[0]->statuses_count;
        $index = 0;
        $prev_id = 0;
        $tw = array();
        while ($index<$total && $index<3200) {
            if ($index == 0) {
                $params = array("screen_name" => $screen_name, "count"=>200,
                    "include_rts"=>true,'exclude_replies'=>false);
            } else {
                $params = array("screen_name" => $screen_name, "count" => 200,
                    "max_id" => $prev_id, "include_rts"=>true,'exclude_replies'=>false);
            }

            $tweets = $connection->get("statuses/user_timeline", $params);
            if ($tweets == null) {
                break;
            }

            foreach ($tweets as $tweet) {
                //print_r($tweet);
                $tw[$index]['no'] = $index;
                $tw[$index]['id']=$tweet->id_str;
                $tw[$index]['text']=$tweet->text;
                $index +=1;
                $prev_id = $tweet->id_str;
            }
        }
        return $tw;
    }
}
