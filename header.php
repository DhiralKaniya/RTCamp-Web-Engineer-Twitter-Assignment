<?php
/**
 * Header of the page..!!
 */
?>
<nav class="navbar navbar-default navbar-static-top bg-primary" id="header">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed"
              data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      <p class="navbar-brand" > <span class="fa fa-twitter"></span> RTCamp Twitter Challenge</p>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

      <ul class="nav navbar-nav navbar-right">
        <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle"
               href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
              Download<span class="caret"></span>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <ul>
                <li class="dropdown-item"><a href="Controller.php?csv-format=true">csv</a></li>
                <li class="dropdown-item"><a href="Controller.php?json-format=true">json</a></li>
                <li class="dropdown-item"><a href="Controller.php?google-spreadsheet=true">SpreadSheet</a></li>
                <li class="dropdown-item"><a href="Controller.php?xls-format=true">xls</a></li>
              </ul>
            </div>
          </li>
        <li class="nav-item"><a class="nav-link" href="Controller.php?logout=true">Logout</a></li>
      </ul>
        <form class="navbar-form" style="text-align: center;"
              role="search" action="users.php" method="post">
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1">@</span>
                <input type="text" class="form-control" placeholder="Twitter Public User"
                       aria-label="Username" aria-describedby="basic-addon1" id="username" name = "username">
            </div>
        </form>
    </div>
  </div>
</nav>
