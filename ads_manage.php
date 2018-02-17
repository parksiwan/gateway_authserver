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
// change for ad
$ads = new Ads(0, 0, '', '', 0, '');
$ads_list = $ads->getAds();

?>

<!doctype html>
<html>
    <?php include("includes/head.php"); ?>
    <body>
        <?php include("includes/navigation.php"); ?>
        <div class="container">
            <div class="row">
              <main class="col-md-12">
                  <!-- products -->
                  <div>
                      <h3>Ads management</h3>
                 
                      <form action="register_ads.php">
            		      <p class="text-right">
            			  <button type="submit" name="upload-button" class="btn btn-primary">Upload Ads</button>						
            			  </p>
        			  </form>
                  </div>
                  
                  <?php
                  if (count($ads_list) > 0) 
                  {
                      $counter = 0;
                      echo "<table class=\"table table-hover\">";
                      echo "<thead><tr><th>Title</th><th>Description</th>
                            <th>Image file</th><th>Price</th></tr></thead>";
                      echo "<tbody>";
                      foreach ($ads_list as $ad) 
                      {
                          echo "<tr>";
                          $id = $ad["pk_id"];
                          $name = $ad["ads_name"];
                          $description = $ad["description"];
                          $price = $ad["price_per_click"];
                          $image = $ad["image_file_name"];
                         
                          echo "<td><div class=\"col-md-3\"><a href=\"edit_ads.php?id=$id\">$name</a></div></td>";
                          echo "<td><div class=\"col-md-4\"><p>$description</p></div></td>";
                          echo "<td><div class=\"col-md-2\">$price</div></td>";
                          echo "<td><div class=\"col-md-3\">$image</div></td>";
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