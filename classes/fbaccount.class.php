<?php
class FBAccount extends Database 
{
    private $_facebook_id;
    
    public function __construct($facebook_id) 
    {
        parent::__construct();    // call the parent's construct method
        $this->_facebook_id = $facebook_id;
    }
    
    
    public function findFacebookId() 
    {
        $query = "SELECT account_id FROM facebook_account WHERE facebook_id = ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("s", $this->_facebook_id);
        if ($statement->execute()) 
        {
            $result = $statement->get_result();
            if ($result->num_rows > 0) 
            {
                $items = $result->fetch_assoc();
                $account_id = $items["account_id"];
                $this->_message["type"] = "success";
                $this->_message["text"] = "id is correct";
                return $account_id;
            } 
            else 
            {
                $this->_message["type"] = "fail";
                $this->_message["text"] = "id doesn't not exists";
                return 0;
            }
        }
        $statement->close();
    }
    
    public function createFacebookAccount($last_id)
    {
        $query = "INSERT INTO facebook_account (account_id, facebook_id) 
                                VALUES (?, ?)";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("is", $last_id, $this->_facebook_id);
        if ($statement->execute()) 
        {
            //account has been created
            $this->_message["type"] = "success";
            $this->_message["text"] = "Account has been created.";
            return true;
        } 
        else 
        {
            if ($this->connection->errno == "1062") 
            {
                $this->_message["errors_facebook_id"] = "id exists";  // duplicate email
            }
            $this->_message["type"] = "danger";
            $this->_message["text"] = "There has been an error.";
            return false;
        }
    }
}

?>