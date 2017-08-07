<!DOCTYPE html>
<html>
	<head>
		<title>RTCamp Demo | Home</title>
		<?php 
			require('Controller.php');
			require('config.php');	
			if(isset($_SESSION['data']))
				$data = $_SESSION['data'];
			else
				header('location:index.php');
		?>
		<script type="text/javascript" src="lib\js\main.js"></script>
	</head>
	<body>
		<?php 
			require('header.php');
		?>
		
		<div class="jumbotron">
			<div class="container">
			<div class="row" align="center" id = "title"><?php echo $data->screen_name; ?>'s tweet</div>
			<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
				
				<!--Wrapper item -->
				<div class="carousel-inner">
					
				</div>
			</div>
			<div class="frmSearch">
				<div id="seach-follower-box">
					<input type="text" name="search_box" id="search_box" placeholder="Search followers" class="form-control"/>
					<div id="suggesstion-box">
					</div>
				</div>
			</div>
			<div class="page-header">
				<h3>Your Followers</h3>
			</div>
			<div id="followers" class="followers">
				<li id ="follower_loading">Loading Your Followers..!!</li>
			</div>
			</div>
			
		</div>
		<?php 
			require('footer.php');
		?>
	</body>
</html>