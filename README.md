# RTCamp-Web-Engineer-Twitter-Assignment
Twitter Timeline Challenge

### 	Assignment Details

#### 	Part-1: User Timeline
1. Start => User visits your script page.

2. User will be asked to connect using his Twitter account using Twitter Auth.

3. After authentication, your script will pull latest 10 tweets from his “home” timeline.

4. 10 tweets will be displayed using a jQuery-slideshow.


##### 	Part-2: Followers Timeline
1. Below jQuery-slideshow (in step#4 from part-1), display list 10 followers (you can take any 10 random followers).

2. Also, display a search followers box. Add auto-suggest support. That means as soon as user starts typing, his followers will start showing up.

3. When user will click on a follower name, 10 tweets from that follower’s user-timeline will be displayed in same jQuery-slider, without page refresh (use AJAX).



##### 	Part-3: Download Tweets
1. There will be a download button to download all tweets for logged in user.

2. Download can be performed in one of the following formats i.e. You choose the format you want.It would act as an advantage if you give the option to download the tweets in all the following formats:

	csv, xls, google-spreadhseet, pdf, xml and json formats.

3. For Google-spreadsheet export feature, your app-user must have Google account. Your app should ask for permission to create spreadsheet on user’s Google-Drive.

4. Once user clicks download button (after choosing option) all tweets for logged in users should be downloaded.

##### Additional Task :- 
1. On searching for any public account on Twitter it should allow the logged in user to download all the tweets of any public account.
   
2. Tweets should be downloaded in PDF format.

####	Task Implemented

1. Part-1 

2. Part-2

3. Part-3

4. Addditional Task

##### Limitation 
   
1. You can search up to 3200 followers.

2. You can download up to 3200 tweets.
	
#####	Front-End Technology

1. HTML

2. CSS

3. Java Script

#####	Codding Structure

##### MVC Codding structure has been follow.

1. Model files :- Functions.php, model.php and config.php

2. View files :- index.php , home.php, header.php, footer.php 

3. Controller file :- Controller.php

###### Note :- config.php specify configuration files which used in project(like :- bootstrap,js,css.).

##### 	Back-End Technology

1. PHP
    
    Handling server side scripting. Basically deal with Twitter API. 

2. Mysql 
    
    Basically mysql server is used for store data and use buffered memory of the server. 

##### 	Third-Party API and FrameWorks

1. [Bootstrap](http://getbootstrap.com/)

2. [jQuery](https://jquery.com/)

3. [Twitter oauth](https://dev.twitter.com/oauth)

4. [Twitter oauth abraham library](https://github.com/abraham/twitteroauth)

5. [Google Drive API](https://developers.google.com/drive/v3/web/quickstart/php)

6. [FPDF](http://www.fpdf.org/)

##### 	Tools

1. [PHPStorm](https://www.jetbrains.com/phpstorm/)

2. [Apache Server 2.0](https://httpd.apache.org/download.cgi)

3. [000Webhost service-for hosting purpose](https://www.000webhost.com/)

4. [PHPCS-Plugin in PHPStorm](https://confluence.jetbrains.com/display/PhpStorm/PHP+Code+Sniffer+in+PhpStorm)

### Demo link
* [Demo](https://rtdemo.000webhostapp.com/)
