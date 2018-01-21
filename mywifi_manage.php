<?php
session_start();
include("autoloader.php");

$customer = new Customer();
$customer_list = $customer->getAllCustomers();
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
                  <h4>Products</h4>
                  <?php
                  if (count($customer_list) > 0) 
                  {
                      
                      $counter = 0;
                      foreach ($customer_list as $cust) 
                      {
                          $id = $cust["pk_id"];
                          $name = $cust["username"];
                          $email = $cust["email"];
                          $access_level = $cust["access_level_name"];
                          $internet_package = $cust["internet_package_name"];
                          //$image = $product["image_file_name"];
                         
                          // create bootstrap row
                          echo "<div class=\"row\">
                          <div class=\"col-md-3\">$name</div>
                          <div class=\"col-md-3\">$email</div>
                          <div class=\"col-md-3\">$access_level</div>
                          <div class=\"col-md-3\">$internet_package</div>
                          </div> ";
                      }
                  }
                  ?>
              </main>
            </div>
        </div>
    </body>
</html>