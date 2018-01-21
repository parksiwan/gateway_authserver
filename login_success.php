<?php
session_start();


header( "refresh:5;url=http://www.google.com" );
?>

<!doctype html>
<html>
    <?php include("includes/head.php"); ?>
    <body>
        <script>
          window.fbAsyncInit = function() {
            FB.init({
              appId      : '389700194801084',
              cookie     : true,
              xfbml      : true,
              version    : 'v2.11'
            });
              
            FB.AppEvents.logPageView();   
              
          };
        
          (function(d, s, id){
             var js, fjs = d.getElementsByTagName(s)[0];
             if (d.getElementById(id)) {return;}
             js = d.createElement(s); js.id = id;
             js.src = "https://connect.facebook.net/en_US/sdk.js";
             fjs.parentNode.insertBefore(js, fjs);
           }(document, 'script', 'facebook-jssdk'));
        </script>
        
        <div class="container text-center"> 
        	<header>
        		<div  class="navbar-header">
        		  <img src="images/logo5.png" width="50px">
        		</div>
        		<div  class="navbar-header">
        			<h1>myWiFi (Broadband Service)</h1>	
        		</div>
        	</header>
        	<div class="feature">
        		<a href="index.php">Free Website</a></li>				
        	</div>

        	<div class="row">
        		<div class="col-md-6 well">
        			<div class="login_div">
        				<h3>Login Success</h3>
        				<p><strong>Hi <?php echo $_SESSION['username']; ?> !</strong></p>
        			</div>
        			
        			<div class="alert alert-success fade in">				
        				<p><strong>Thank you for using myWiFi. After 5 seconds, you will be redirected to the Internet.</strong></p>
        			</div>
        			
        		</div>
        		<div class="col-md-6">       
        			<div class=well">				      
        				<div class="well">					
        					<img src="images/ad1.jpg" width="500px">
        				</div>
        				<div class="thumbnail">
        					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. 
        					Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum.
        				</div>
        			</div>
        		</div>
        	</div> 
        	<footer class="container-fluid text-center">
        	<p>Powered By AIT Communication 2017</p>
        	</footer>	
        </div>
    </body>
</html>