<?php
session_start();
//echo session_id();
include("autoloader.php");


// Check if file already exists
$target_dir = "images/";
$file_name = basename($_FILES["fileToUpload"]["name"]);
//echo "base: " . $file_name = $_FILES["fileToUpload"]["name"];
//$file_name = basename($_FILES["fileToUpload"]);
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
//echo "target_file : " . $target_file;
    
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
    
    if (file_exists($target_file)) 
    {
        $messages["image"] = "Sorry, file already exists.";
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) 
    {
        $messages["image"] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    }
    
    
    $ads = new Ads(0, $ads_category, $ads_name, $ads_description, $price, $file_name);
    
    if (count($messages) == 0) 
    {
        //echo "image : " . $image;
        $image_id = $ads->insertImageFile($file_name);
        //echo "image-id :" . $image_id;
        $ads->registerAds($image_id, $ads_name);
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        header('Location: ads_manage.php');
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
                        <form id="ads-register-form" method="post" enctype="multipart/form-data" action="register_ads.php">
                            <h2>Register for Ads</h2>
                            <?php
                            if (count($messages) > 0)
                            {
                                $class = "alert-" . $message["type"];
                                $text = $message["text"];
                                echo "<div class=\"alert $class\">$text</div>";
                            }
                            ?>
                            <div class="form-group <?php echo $error_class; ?>">
                                <label for="ads_categories">Ads Categories</label>
                                <select class="form-control" name="ads_category">
                                  <option value="1">restaurant</option>
                                  <option value="2">liquor</option>
                                  <option value="3">electronics</option>
                                  <option value="4">clothings</option>
                                  <option value="5">accessories</option>
                                </select>
                            </div>
                            <div class="form-group <?php echo $error_class; ?>">
                                <label for="ads_name">Ads Name</label>
                                <input class="form-control" type="text" name="ads_name" id="ads_name" placeholder="ads name" >
                                <span class="help-block"><? echo $messages["ads_name"];?></span>
                            </div>
                            
                            <div class="form-group <?php echo $error_class; ?>">
                                <label for="ads_description">Description</label>
                                <textarea class="form-control" rows="5" name="ads_description" id="ads_description" placeholder="description"></textarea>
                                
                                <span class="help-block"><? echo $messages["ads_description"];?></span>
                            </div>
                            <div class="form-group <?php echo $error_class; ?>">
                                <label for="price">Price</label>
                                <input class="form-control" type="number" name="price" id="price" placeholder="0.001" step="0.001" min="0" max="10" >
                                <span class="help-block"><? echo $messages["price"];?></span>
                            </div>
                            <div class="form-group <?php echo $error_class; ?>">
                                <label for="image">Image</label>
                                <div class="file-field">
                                    <input type="file" name="fileToUpload" id="fileToUpload">
                                </div>
                                <span class="help-block"><? echo $messages["image"];?></span>
                            </div>
                            <br>
                            <button type="reset" class="btn btn-warning">Clear Form</button>
                            <button type="submit" class="btn btn-success">Register</button>
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