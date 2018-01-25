<?php
class Service extends Database 
{
    public function __construct() 
    {
        parent::__construct();    // call the parent's construct method
    }
    
    public function insertMacAddress($account_id, $mac_address)
    {
        $date = date('Y-m-d H:i:s');
        $query = "INSERT INTO mac_list (mac_address, account_id, created_date, last_internet_access) 
                                VALUES (?, ?, ?, '0000-00-00 00:00:00')";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("sis", $mac_address, $account_id, $date);
        if ($statement->execute()) 
        {
            //account has been created
            $this->_message["type"] = "success";
            $this->_message["text"] = "mac address has been inserted.";
            $last_id = $statement->insert_id;
            return $last_id;;
        } 
        else 
        {
            $this->_message["type"] = "fail";
            $this->_message["text"] = "There has been an error.";
            return 0;
        }
    }
    
    public function findMacAddress($mac_address) 
    {
	    $query = "SELECT account_id FROM mac_list WHERE mac_address = ?";
        $statement = $this->connection->prepare($query);
        $statement -> bind_param( "s", $mac_address );
        if ($statement->execute()) 
        {
            $result = $statement->get_result();
            
            if ($result->num_rows > 0)    // check number of rows in result
            {
                $row = $result->fetch_assoc();
                return $row["account_id"];
            } 
            else 
            {
                return 0;
            }
        } 
        else 
        {
            return 0;
        }
        $statement->close();
    }
    
    public function getUsername($mac_address) 
    {
        //$mac_array = array();
	    $query = "SELECT accounts.username FROM mac_list 
	              INNER JOIN accounts ON accounts.account_id = mac_list.account_id 
	              WHERE mac_address =" . $mac_address;
        $statement = $this->connection->prepare($query);
        if ($statement->execute()) 
        {
            $result = $statement->get_result();
            if ($result->num_rows > 0)    // check number of rows in result
            {
                $usernames = array();
                while ($row = $result->fetch_assoc()) 
                {
                    array_push($usernames, $row);
                }
                return $macs;
            } 
            else 
            {
                return null;
            }
        } 
        else 
        {
            return null;
        }
        $statement->close();
    }
    
    //public function insertMacaddress
}
?>