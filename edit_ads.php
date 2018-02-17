<?php
session_start();
//echo session_id();
include("autoloader.php");

if(isset($_GET['id']) && intval($_GET['id'])) 
{
    $ads_id = intval($_GET['id']); //no default
    $ads = new Ads($ads_id, 0, '', '', 0, '');
    $ads_info = array();
    $ads_info = $ads->getAdsDetails($ads_id);
    $_SESSION["ads_id"] = $ads_id;
}

// file preparation
//$target_dir = "images/";
//$file_name = basename($_FILES["fileToUpload"]["name"]);
//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
//$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $ads_category = intval($_POST["ads_category"]);
    $ads_name = strval($_POST["ads_name"]);
    $ads_description = strval($_POST["ads_description"]);
    $price = doubleval($_POST["price"]);
    //$image = $_POST["image"];
    
    

    $messages = array();  // = [];
    if ($ads_name == '')
    {
        $messages["ads_name"] = "no ads name";
        
    }
    
    if ($ads_description == '')
    {
        $messages["ads_description"] = "no ads description";
        
    }
    
    if ($price < 0)
    {
        $messages["price"] = "price can not be negative";
        
    }
    
    
    $ads = new Ads(0, $ads_category, $ads_name, $ads_description, $price, $file_name);
    
    if (count($messages) == 0) 
    {
        $ads_id = intval($_SESSION["ads_id"]);
        if ($ads->editAds($ads_id, $ads_category, $ads_name, $ads_description, $price))
        {
            header('Location: ads_manage.php'); 
        }
        
    }    
}
?>

<!doctype html>
<html>
    <?php include("includes/head.php"); ?>
    <body>
        <?php include("includes/navigation.php"); ?>
        <div class="container text-center"> 
        	<div class="container">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3" >
                        <form id="ads-edit-form" method="post"  action="edit_ads.php">
                            <h2>Edit Ads</h2>
                            <?php
                            if (count($messages) > 0)
                            {
                                
                                $text = $messages["text"];
                                echo "<div class=\"alert $class\">$text</div>";
                            }
                            ?>
                            <div class="form-group <?php echo $error_class; ?>">
                                <label for="ads_categories">Ads Categories</label>
                                <select class="form-control" name="ads_category" value="<?php echo $ads_info[0]["category_id"]; ?>">
                                  <option value="1" <?php if($ads_info[0]["category_id"] == '1'){echo("selected");}?> >restaurant</option>
                                  <option value="2" <?php if($ads_info[0]["category_id"] == '2'){echo("selected");}?>>liquor</option>
                                  <option value="3" <?php if($ads_info[0]["category_id"] == '3'){echo("selected");}?>>electronics</option>
                                  <option value="4" <?php if($ads_info[0]["category_id"] == '4'){echo("selected");}?>>clothings</option>
                                  <option value="5" <?php if($ads_info[0]["category_id"] == '5'){echo("selected");}?>>accessories</option>
                                </select>
                            </div>
                            <div class="form-group <?php echo $error_class; ?>">
                                <label for="ads_name">Ads Name</label>
                                <input class="form-control" type="text" name="ads_name" id="ads_name" placeholder="ads name" value="<?php echo $ads_info[0]["ads_name"]; ?>">
                                <span class="help-block"><? echo $messages["ads_name"];?></span>
                            </div>
                            
                            <div class="form-group <?php echo $error_class; ?>">
                                <label for="ads_description">Description</label>
                                <textarea class="form-control" rows="5" name="ads_description" id="ads_description" placeholder="description"><?php echo $ads_info[0]["description"]; ?></textarea>
                                <span class="help-block"><? echo $messages["ads_description"];?></span>
                            </div>
                            <div class="form-group <?php echo $error_class; ?>">
                                <label for="price">Price</label>
                                <input class="form-control" type="number" name="price" id="price" placeholder="0.001" step="0.001" min="0" max="10" value="<?php echo $ads_info[0]["price_per_click"]; ?>">
                                <span class="help-block"><? echo $messages["price"];?></span>
                            </div>
                            
                            <br>
                            <button type="reset" class="btn btn-warning">Clear Form</button>
                            <button type="submit" class="btn btn-success">Edit</button>
                        </form>
                    </div>
                </div>
            </div> 
        </div>
        <footer class="container-fluid text-center">
        	<p>Powered By AIT Communication 2017</p>
        </footer>
    </body>
</html>