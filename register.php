<?php
session_start();
echo session_id();
include("autoloader.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $username = $_POST["username"];
    $password = $_POST["password1"];
    $re_password = $_POST["password2"];
    $email = $_POST["email"];
    $account = new Account($username, $password, $re_password, $email);
    
    $messages = array();  // = [];
    if (!$account->checkUsername())
    {
        $messages = $account->getAllMessage();
        
    }
    
    if (!$account->checkEmail())
    {
        $messages = $account->getAllMessage();
        
    }
    
    if (!$account->checkPassword())
    {
        $messages = $account->getAllMessage();
        
    }
    
    //count the number of errors, if 0 then proceed
    if (count($messages) == 0) 
    {
        $account_id = $account->createAccount();
        echo "acount id : " . $account_id;
        if ($account_id > 0)
        {
            $session_id = session_id();
            $messages = $account->emailVerificationLink($session_id);
            $_SESSION["account_id"] = $account_id;
            echo "mac :" , $_SESSION["mac_address"];
            echo "id :" , $_SESSION["account_id"];
        }
    }
    echo $messages;
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
                        <form id="register-form" method="post" action="register.php">
                            <h2>Register for account</h2>
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
                                <input class="form-control" type="text" name="username" id="username" placeholder="username88" value="<?php echo $username; ?>">
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
                                <input class="form-control" type="email" name="email" id="email" placeholder="username88@domain.com" value="<?php echo $email; ?>">
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
                                <label for="password">Password</label>
                                <input class="form-control" type="password" name="password1" id="password1" placeholder="minimum 8 characters">
                            </div>
                            <div class="form-group <?php echo $error_class; ?>">
                                <label for="password">Retype Password</label>
                                <input class="form-control" type="password" name="password2" id="password2" placeholder="please retype your password">
                                <span class="help-block"><? echo $errors["password"];?></span>
                            </div>
                            <br>
                            <button type="reset" class="btn btn-warning">Clear Form</button>
                            <button type="submit" class="btn btn-success">Register</button>
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