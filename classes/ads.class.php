<?php
class Ads extends Database 
{
    public function __construct() 
    {
        parent::__construct();    // call the parent's construct method
    }
    
    public function getProducts() 
    {
        // get all products from database
        $query = "select products.id, products.name, products.description, products.price, Images.image_file_name
                  from products
                  inner join products_images on products_images.product_id = products.id
                  inner join Images on products_images.image_id = Images.image_id group by products.id";
        $statement = $this->connection->prepare($query);
        if ($statement->execute()) 
        {
            $result = $statement->get_result();
            // check number of rows in result
            if ($result->num_rows > 0) 
            {
                $products = array();
                while ($row = $result->fetch_assoc()) 
                {
                    array_push($products, $row);
                }
                return $products;
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
}
?>