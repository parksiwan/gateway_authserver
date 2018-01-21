<?php
class Customer extends Database 
{
    private $_customer_id;
    //private $_password;
    
    private $_level;
    private $_message;
    
    
    public function __construct() 
    {
        parent::__construct();    // call the parent's construct method
        //$this->_username = $username;
        //$this->_password = $password;
        
        //$this->_email = $email;
        $this->_message = array();
        
    }
    
    public function getAllCustomers() 
    {
        // get all products from database
        $query = "SELECT ac.pk_id, ac.username, ac.email, al.access_level_name, ip.internet_package_name FROM accounts as ac
                  INNER JOIN access_level as al ON ac.access_level = al.access_level_id
                  INNER JOIN internet_package as ip ON ac.internet_package = ip.internet_package_id";
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