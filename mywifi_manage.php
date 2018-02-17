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
}

$user = new User();
$user_list = $user->getAllUsers();

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
        <div class="container">
            <div class="row">
              <main class="col-md-12">
                  <!-- products -->
                  <h3>Guests Profile</h3>
                  <?php
                  if (count($user_list) > 0) 
                  {
                      $counter = 0;
                      echo "<table class=\"table table-hover\">";
                      echo "<thead><tr><th>Username</th><th>Email</th>
                            <th>Access Level</th><th>Internet Package</th></tr></thead>";
                      echo "<tbody>";
                      foreach ($user_list as $user) 
                      {
                          echo "<tr>";
                          $id = $user["pk_id"];
                          if (empty($user["username"]))
                          {
                              $name = $user["full_name"];
                          }
                          else 
                          {
                              $name = $user["username"];
                          }
                          $email = $user["email"];
                          $access_level = $user["access_level_name"];
                          $internet_package = $user["internet_package_name"];
                          //$image = $product["image_file_name"];
                         
                          echo "<td><div class=\"col-md-3\"><a href=\"edit_user_profile.php?id=$id\">$name</a></div></td>";
                          echo "<td><div class=\"col-md-3\">$email</div></td>";
                          echo "<td><div class=\"col-md-3\">$access_level</div></td>";
                          echo "<td><div class=\"col-md-3\">$internet_package</div></td>";
                          echo "</tr>";
                      }
                      echo "</tbody>";
                      echo "</table>";
                  }
                  ?>
              </main>
            </div>
        </div>
        <footer class="container-fluid text-center">
        	<p>Powered By AIT Communication 2017</p>
        </footer>
    </body>
</html>