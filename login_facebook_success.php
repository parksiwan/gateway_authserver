<?php
session_start();
include("autoloader.php");

require_once __DIR__ . '/vendor/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => '389700194801084', // Replace {app-id} with your app id
  'app_secret' => 'cc33f211833e6be8df016347a05c56f1',
  'default_graph_version' => 'v2.11',
  ]);

$helper = $fb->getRedirectLoginHelper();

try 
{
    $accessToken = $helper->getAccessToken();
} 
catch(Facebook\Exceptions\FacebookResponseException $e) 
{
    echo 'Graph returned an error: ' . $e->getMessage();  // When Graph returns an error
    exit;
} 
catch(Facebook\Exceptions\FacebookSDKException $e) 
{
    echo 'Facebook SDK returned an error: ' . $e->getMessage();  // When validation fails or other local issues
    exit;
}

if (!isset($accessToken)) 
{
    if ($helper->getError()) 
    {
        header('HTTP/1.0 401 Unauthorized');
        echo "Error: " . $helper->getError() . "\n";
        echo "Error Code: " . $helper->getErrorCode() . "\n";
        echo "Error Reason: " . $helper->getErrorReason() . "\n";
        echo "Error Description: " . $helper->getErrorDescription() . "\n";
    } 
    else 
    {
        header('HTTP/1.0 400 Bad Request');
        echo 'Bad request';
    }
    exit;
}

// Logged in
// echo 'Access Token:';
// var_dump($accessToken->getValue());

// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
//echo 'Metadata:';
//var_dump($tokenMetadata);

// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId('389700194801084'); // Replace {app-id} with your app id

// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
$tokenMetadata->validateExpiration();

if (! $accessToken->isLongLived()) 
{
    // Exchanges a short-lived access token for a long-lived one
    try 
    {
        $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    } 
    catch (Facebook\Exceptions\FacebookSDKException $e) 
    {
        echo getMessage();
        exit;
    }
    
    echo 'Long-lived:';
    var_dump($accessToken->getValue());
}

try 
{
  // Returns a `Facebook\FacebookResponse` object
  $response = $fb->get('/me?fields=id,name,email', $accessToken);
} 
catch(Facebook\Exceptions\FacebookResponseException $e) 
{
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} 
catch(Facebook\Exceptions\FacebookSDKException $e) 
{
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}
$user = $response->getGraphUser();
$fb_account_name = $user['name'];
$fb_account_id = $user['id'];
$fb_account_email = $user['email'];

$fb_account = new FBAccount($fb_account_id);
$id = $fb_account->findFacebookId();
if ($id == 0)
{
    $account = new Account("", "", "", $fb_account_email);
    $last_id = $account->createAccount();  
    $fb_account->createFacebookAccount($last_id);
    $account->updateLastlogin($last_id);
}
else
{
    $account = new Account("", "", "", $fb_account_email);
    $account->updateLastlogin($id);
}

$_SESSION['fb_access_token'] = (string) $accessToken;

// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
//header('Location: https://example.com/members.php');

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
        				<p><strong>Hi <?php echo $user['name']; ?> !</strong></p>
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