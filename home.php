<?php
/**
 * Home Page Defination..!!
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <title>RTCamp Demo | Home</title>
        <?php
            require('Controller.php');
            require('config.php');
            $data = null;
        if (isset($_SESSION['data'])) {
            $data = $_SESSION['data'];
        } else {
            header('location:index.php');
        }
        ?>
        <script type="text/javascript" src="lib\js\main.js"></script>
    </head>
    <body>
    <?php
        require('header.php');
    ?>
    <div class="row main-content" >
        <div class="col-md-9 left-content">
            <div class="row" align="center" id = "title">
                <?php echo $data->screen_name; ?>'s tweet
            </div>
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                <!--Wrapper item -->
                <div class="carousel-inner">
                </div>
            </div>
        </div>
        <div class="col-md-3 right-content">
            <div class="row">
                <div class="col-md-12">
                    <input name="search_box" id="search_box" placeholder="Search followers"
                           class="form-control"/>
                </div>
            </div>
            <div id="followers" class="followers">
                <a id ="follower_loading">Loading Your Followers..!!</a>
            </div>
            <div id="seach-follower-box">
            </div>
        </div>

    </div>
    </body>
</html>