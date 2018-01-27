<?php
include("autoloader.php");


if (isset($_GET['token']) && isset($_GET['user']) && isset($_GET['sid']))
{
    session_id(strval($_GET['sid']));
    session_start();
    echo session_id();
    $username = strval($_GET['user']);
    $verify_token = strval($_GET['token']);
    $account = new Account($username, "", "", "", "", "", "");
    if ($account->findUsername())
    {
        $account->updateActionAndToken(1, $verify_token); 
        echo "session_mac :" . $_SESSION['mac_address'];
        echo "id :" . $_SESSION['account_id'];
        if (isset($_SESSION['account_id']) && isset($_SESSION['mac_address']))
        {
             $service = new Service();
             $account_id = $_SESSION['account_id'];
             $mac_address = $_SESSION['mac_address'];
             $service->insertMacAddress($account_id, $mac_address);
        }
    }
	header( "refresh:5;url=http://www.google.com" );
	/* connect to the db */
    //$service = new Service();
    
	/* grab the posts from the db */
	//$mac_array = $service->findMacAddress($mac_address);
	
	/* output in necessary format */
	//header('Content-type: application/json');
	//echo json_encode(array('macs'=>$mac_array));
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