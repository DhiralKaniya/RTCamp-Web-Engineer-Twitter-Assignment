<?php
/**
 * Created by PhpStorm.
 * User: The_King
 * Date: 8/18/2017
 * Time: 12:53 PM
 */
?>
<html>
    <head>
        <title>RTCamp Demo | Users</title>
    <?php
    require('Controller.php');
    require('config.php');
    if(!isset($_REQUEST['username'])){
        ?>
        <script type="text/javascript">
            alert("Invalid Page Call..");
            window.location.href="home.php";
        </script>
        <?php
    }else{
        $username = $_REQUEST['username'];
    }
    ?>
        <script src="lib/js/users.js" type="text/javascript"></script>
    </head>
    <body>
        <?php
            require('header.php');
        ?>
        <div class="container">
            <div class="row" id="users" value="<?php echo $username; ?>">
                <div class="col-md-3 col-lg-3 "></div>
                <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
                    <div class="card hovercard">
                        <div class="cardheader">
                        </div>
                        <div class="avatar userProfilePic">
                        </div>
                        <div class="info">
                            <div class="title userName">
                            </div>
                            <div class="desc userScreenName"></div>
                            <div class="desc userLocation"></div>
                            <div class="desc userTweetes"></div>
                            <div class="desc uFollowers"></div>
                        </div>
                        <div class="bottom">
                            <form method="post" action="pdf.php?download-pdf=true&username=<?php echo $username; ?>">
                                <button type="submit" class="btn btn-primary ">
                                    <i class="fa fa-file-pdf-o"></i>
                                    | Download PDF
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3"></div>
                </div>
            </div>
        </div>
    </body>
</html>