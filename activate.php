<?php
include("autoloader.php");


if (isset($_GET['token']) && isset($_GET['user']) && isset($_GET['sid']))
{
    session_id(strval($_GET['sid']));
    session_start();
    //echo session_id();
    $username = strval($_GET['user']);
    $verify_token = strval($_GET['token']);
    $account = new Account($username, "", "", "", "", "", "");
    if ($account->findUsername())
    {
        $account->updateActionAndToken(1, $verify_token); 
        //echo "session_mac :" . $_SESSION['mac_address'];
        //echo "id :" . $_SESSION['account_id'];
        if (isset($_SESSION['account_id']) && isset($_SESSION['mac_address']))
        {
             $service = new Service();
             $account_id = $_SESSION['account_id'];
             $mac_address = $_SESSION['mac_address'];
             $service->insertMacAddress($account_id, $mac_address);
        }
        $getdata = http_build_query( array(
                    'mac_address' => $mac_address,
                    'account_id' => $account_id,
                    'internet_package'=>1
                     ));
        file_get_contents('http://mywifigw.ddns.net/service_iptables.php?'.$getdata, false);
        //$url = 'http://mywifigw.ddns.net/service_iptables.php?mac_address=$mac_address&account_id=$account_id&internet_package=1';
        //echo $url;
        //$response = file_get_contents($url);
    
    }
	header( "refresh:5;url=http://www.google.com" );

}

?>

<!doctype html>
<html>
    <?php include("includes/head.php"); ?>
    <body>
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
        				<p><strong>Hi <?php echo $username ?> !</strong></p>
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