<?php
class Ads extends Database 
{
    private $_ads_id;
    private $_ads_category;
    private $_ads_name;
    private $_description;
    private $_price;
    private $_image_file_name;
    private $_message;
    
    public function __construct($ads_id, $ads_category, $ads_name, $description, $price, $image_file_name) 
    {
        parent::__construct();    // call the parent's construct method
        $this->_ads_id = $ads_id;
        $this->_ads_category = $ads_category;
        $this->_ads_name = $ads_name;
        $this->_description = $description;
        $this->_price = $price;
        $this->_image_file_name = $image_file_name;
        $this->_message = array();
    }
    
    public function getAds() 
    {
        // get all products from database
        $query = "select ads.pk_id, ads.ads_name, ads.description, ads.price_per_click, images.image_file_name
                  from ads
                  inner join ads_categories on ads_categories.ads_id = ads.pk_id 
                  inner join ads_image on ads_image.ads_id = ads.pk_id
                  inner join images on images.pk_id = ads_image.pk_id order by ads_categories.pk_id, ads.pk_id";
        $statement = $this->connection->prepare($query);
        if ($statement->execute()) 
        {
            $result = $statement->get_result();
            // check number of rows in result
            if ($result->num_rows > 0) 
            {
                $ads = array();
                while ($row = $result->fetch_assoc()) 
                {
                    array_push($ads, $row);
                }
                return $ads;
            } 
            else 
            {
                return false;
            }
        } 
        else 
        {
            return false;
        }
        $statement->close();
    }
    
    public function getAdsDetails($ads_id)
    {
        // get all products from database
        $query = "select ads_categories.category_id, ads.ads_name, ads.description, ads.price_per_click, images.image_file_name
                  from ads
                  inner join ads_categories on ads_categories.ads_id = ads.pk_id 
                  inner join ads_image on ads_image.ads_id = ads.pk_id
                  inner join images on images.pk_id = ads_image.pk_id where ads.pk_id = ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("i", $ads_id);
        
        if ($statement->execute()) 
        {
            $result = $statement->get_result();
            // check number of rows in result
            if ($result->num_rows > 0) 
            {
                $ads = array();
                while ($row = $result->fetch_assoc()) 
                {
                    array_push($ads, $row);
                }
                return $ads;
            } 
            else 
            {
                return false;
            }
        } 
        else 
        {
            return false;
        }
        $statement->close();
    }
    
    public function registerAds($image_id)
    {
        
        $query = "INSERT INTO ads (ads_name, description, price_per_click, modified_date, active) 
                                VALUES (?, ?, ?, '0000-00-00 00:00:00', 1)";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("ssd", $this->_ads_name, $this->_description, $this->_price);
        
        
        if ($statement->execute()) 
        {
            
            //account has been created
            $this->_message["type"] = "success";
            $this->_message["text"] = "Your account has been created.";
            $last_id = $statement->insert_id;
            // keep inserting .....
            $this->insertAdsToCatagory($last_id, $this->_ads_category);
            $this->insertAdsToImage($last_id, $image_id);
            return $last_id;
        } 
        else 
        {
            $this->_message["type"] = "danger";
            $this->_message["text"] = "There has been an error.";
            return 0;
        }
    }
    
    public function insertAdsToCatagory($ads_id, $category_id)
    {
        $query = "INSERT INTO ads_categories (ads_id, category_id, active) 
                                VALUES (?, ?, 1)";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("ii", $ads_id, $category_id);
        
        if ($statement->execute()) 
        {
            //account has been created
            $this->_message["type"] = "success";
            $this->_message["text"] = "Ads to catagory has been created.";
            $last_id = $statement->insert_id;
            return $last_id;
        } 
        else 
        {
            $this->_message["type"] = "danger";
            $this->_message["text"] = "There has been an error.";
            return 0;
        }
    }
    
    public function insertAdsToImage($ads_id, $image_id)
    {
        $query = "INSERT INTO ads_image (ads_id, image_id, active) 
                                VALUES (?, ?, 1)";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("ii", $ads_id, $image_id);
        if ($statement->execute()) 
        {
            //account has been created
            $this->_message["type"] = "success";
            $this->_message["text"] = "Ads to image has been created.";
            $last_id = $statement->insert_id;
            return $last_id;
        } 
        else 
        {
            $this->_message["type"] = "danger";
            $this->_message["text"] = "There has been an error.";
            return 0;
        }
    }
    
    public function insertImageFile($image_file_name)
    {
        $date = date('Y-m-d H:i:s');
        $query = "INSERT INTO images (image_file_name, uploaded_date, caption, active) VALUES (?, ?, ' ', 1)";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("ss", $image_file_name, $date);
        if ($statement->execute()) 
        {
            //account has been created
            $this->_message["type"] = "success";
            $this->_message["text"] = "Image has been created.";
            $last_id = $statement->insert_id;
            return $last_id;
        } 
        else 
        {   
            $this->_message["type"] = "danger";
            $this->_message["text"] = "There has been an error.";
            return 0;
        }
    }
    
    public function updateAdsCategory($ads_id, $ads_category)
    {
        $query = "UPDATE ads_categories SET category_id = ? WHERE ads_id = ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("ii", $ads_category, $ads_id);
        
        if ($statement->execute()) 
        {
            //account has been created
            $this->_message["type"] = "success";
            $this->_message["text"] = "Ads-Category has been updated.";
            return true;
        } 
        else 
        {
            $this->_message["type"] = "danger";
            $this->_message["text"] = "Update error.";
            return false;
        }
    }
    
    public function editAds($ads_id, $ads_category, $ads_name, $ads_description, $price)
    {
        $date = date('Y-m-d H:i:s');  //NOW();
        $query = "UPDATE ads SET ads_name = ?, description = ?, price_per_click = ? WHERE pk_id = ?";
        $statement = $this->connection->prepare($query);
        //echo "id : " . $ads_id;
        $statement->bind_param("ssdi", $ads_name, $ads_description, $price, $ads_id);
        
        if ($statement->execute()) 
        {   
            //account has been created
            $this->_message["type"] = "success";
            $this->_message["text"] = "Ads has been updated.";
            if ($this->updateAdsCategory($ads_id, $ads_category))
            {
                return true;
            }
            else
            {
                return false;
            }
        } 
        else 
        {   
            $this->_message["type"] = "danger";
            $this->_message["text"] = "Update error.";
            return false;
        }
    }
}
?>