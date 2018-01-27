<?php
session_start();
echo session_id();
include("autoloader.php");

if(isset($_GET['id']) && intval($_GET['id'])) 
{
    $account_id = intval($_GET['id']); //no default
    $account = new Account("", "", "", "", "", "", "");
    $account_info = array();
    $account_info = $account->getAccountDetails($account_id);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $username = $_POST["username"];
    $email = $_POST["email"];
    $access_level = $_POST["access_level"];
    $internet_package = $_POST["internet_package"];
    $account = new Account($username, "", "", $email, "", "", "");
    
    $messages = array();  // = [];
    
    if (!$account->checkEmail())
    {
        $messages = $account->getAllMessage();
        
    }
    
    //count the number of errors, if 0 then proceed
    if (count($messages) == 0) 
    {
        $account->updateUserProfile($access_level, $internet_package);
        
    }
}
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
        <?php include("includes/navigation.php"); ?>
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

        	<div class="container">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3" >
                        <form id="register-form" method="post" action="edit_user_profile.php">
                            <h2>Edit User profile</h2>
                            <?php
                            if (count($message) > 0)
                            {
                                $class = "alert-" . $message["type"];
                                $text = $message["text"];
                                echo "<div class=\"alert $class\">$text</div>";
                            }
                            if ($errors["username"])
                                $error_class = "has-error";
                            else 
                                $error_class = "";    
                            ?>
                            <div class="form-group <?php echo $error_class; ?>">
                                <label for="username">Username</label>
                                <input class="form-control" type="text" name="username" id="username" placeholder="username88" value="<?php echo $account_info[0]["username"]; ?>">
                                <span class="help-block"><? echo $errors["username"];?></span>
                            </div>
                            <!-- email -->
                            <?php
                            if ($errors["email"])
                                $error_class = "has-error";
                            else 
                                $error_class = "";    
                            ?>
                            <div class="form-group <?php echo $error_class; ?>">
                                <label for="email">Email Address</label>
                                <input class="form-control" type="email" name="email" id="email" placeholder="username88@domain.com" value="<?php echo $account_info[0]["email"]; ?>">
                                <span class="help-block"><? echo $errors["email"];?></span>
                            </div>
                            <!-- password -->
                            <?php
                            if ($errors["password"])
                                $error_class = "has-error";
                            else 
                                $error_class = "";    
                            ?>
                            <div class="form-group <?php echo $error_class; ?>">
                                <label for="password">Access Level</label>
                                <select class="form-control" name="access_level">
                                  <option value="2">guests</option>
                                  <option value="1">manager</option>
                                </select>
                            </div>
                            <div class="form-group <?php echo $error_class; ?>">
                                <label for="password">Internet Package</label>
                                <select class="form-control" name="internet_package">
                                  <option value="1">standard</option>
                                  <option value="2">premium</option>
                                </select>
                                <span class="help-block"><? echo $errors["password"];?></span>
                            </div>
                            <br>
                            <button type="reset" class="btn btn-warning">Cancel</button>
                            <button type="submit" class="btn btn-success">Save</button>
                        </form>
                    </div>
                    <p><?php echo $messages ?></p>
                </div>
            </div> 
        	<footer class="container-fluid text-center">
        	<p>Powered By AIT Communication 2017</p>
        	</footer>	
        </div>
    </body>
</html>