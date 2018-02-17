<?php
session_start();
include("autoloader.php");

$service = new Service();
$mac_address = $_SESSION["mac_address"];
$result = $service->findMacAddress($mac_address);
if ($result == 0)
{
    $account_id = $_SESSION['account_id'];
    $service->insertMacAddress($account_id, $mac_address);
    $getdata = http_build_query( array(
                    'mac_address' => $mac_address,
                    'account_id' => $account_id,
                    'internet_package'=>1
                     ));
    file_get_contents('http://mywifigw.ddns.net/service_iptables.php?'.$getdata, false);
}


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
        		<h5>  </h5>				
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
        					True Mexical food has both a depth of flavour with its combination of savoury and earthy flavours. Best ever.
        					Try it out !
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