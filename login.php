<?php
session_start();
echo session_id();
include("autoloader.php");
//include("includes/database.php");
//$account = new Account("siwan", "psw1101714");
//$account->createAccount("siwan", "psw1101714", "park_siwan@naver.com");

if(isset($_GET['mac']) && strval($_GET['mac'])) 
{
    $_SESSION["mac_address"] = strval($_GET['mac']); //no default
}

// Legacy Login
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $username = $_POST["userid"];
    $password = $_POST["password"];
    $account = new Account($username, $password, "", "");
    if ($account->login())
    {
        $_SESSION["username"] = $username;
        $_SESSION["account_id"] = $account->findUsername();
        $account->updateLastlogin(0);
        $account->getAccessLevel();
        if (($username == "mywifiadmin") || ($account->getAccessLevel() == 1))
        {   
            header('Location: mywifi_manage.php');    
        }
        else
        {
            header('Location: login_success.php');    
        }
    } 
    else 
    {
        echo $account->getMessage("text");
    }
}

// Facebook Login
require_once __DIR__ . '/vendor/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => '389700194801084', // Replace {app-id} with your app id
  'app_secret' => 'cc33f211833e6be8df016347a05c56f1',
  'default_graph_version' => 'v2.11',
  ]);

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('https://gateway-parksiwan.c9users.io/login_facebook_success.php', $permissions);

// echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
// test script
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
        			    <form id="login-form" method="post" action="login.php">
            				<h3>Login to your account</h3>
            				<div class="form-group">
            					<label class="lbl" for="userid">Username or Email</label>
            					<input type="text" class="form-control" id="userid" name="userid" placeholder="user66 or user@domain.com" />
            					<label for="password">Password</label>
            					<input type="password" class="form-control" id="password" name="password" placeholder="your password" /><br />
            					<p class="text-center">
            						<button type="submit" name="login-button" class="btn btn-primary">Log in</button>
            						<h5>Forgot your password ?</h5>
            					</p>
            				</div>
            			</form>
        			</div>
        			<div class="well">
        				<p><b>Or Login with</b></p>
        				<p> 
        				    <?php echo '<a href="' . htmlspecialchars($loginUrl) . '"><img src="images/so_facebook.png" width="40px"><span class="label label-primary">Log in with Facebook</span><br/><br/></a>'; ?>
        					<img src="images/so_google.png" width="40px"><span class="label label-danger">Log in with Google</span>
        					<form action="register.php">
            					<p class="text-center">
            						<button type="submit" name="login-button" class="btn btn-primary">Create new account</button>						
            					</p>
        					</form>
        				</p>
        			</div>
        			<div class="alert alert-success fade in">				
        				<p><strong>Note:</strong></p>
        				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. 
        				Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum.
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