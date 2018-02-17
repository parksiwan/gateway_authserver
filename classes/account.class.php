<?php
class Account extends Database 
{
    private $_username;
    private $_password;
    private $_re_password;
    private $_email;
    private $_first_name;
    private $_last_name;
    private $_full_name;
    private $_active;
    private $_access_level;
    private $_internet_package;
    private $_message;
    //private $_errors;
    
    public function __construct($username, $password, $re_password, $email, 
                                $first_name, $last_name, $full_name) 
    {
        parent::__construct();    // call the parent's construct method
        $this->_username = $username;
        $this->_password = $password;
        $this->_re_password = $re_password;
        $this->_email = $email;
        $this->_first_name = $first_name;
        $this->_last_name = $last_name;
        $this->_full_name = $full_name;
        $this->_access_level = 2;
        $this->_internet_package = 1;
        $this->_message = array();
        //$this->_errors = array();
    }
    
    public function login() 
    {
        $query = "select pk_id, username, email, password, active, access_level, internet_package from accounts where username=? or email=?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("ss", $this->_username, $this->_username);
        if ($statement->execute()) 
        {
            $result = $statement->get_result();
            if ($result->num_rows > 0) 
            {
                $user = $result->fetch_assoc();
                $account_id = $user["pk_id"];
                $username = $user["username"];
                $email = $user["email"];
                $hash = $user["password"];
                $active = $user["active"];
                if ($active == 0)  // user who has not activated yet
                {
                    $this->_message["type"] = "fail";
                    $this->_message["text"] = "not activated account";
                    return false;
                }
                
                if (password_verify($this->_password, $hash))  //check if pasword matches  -> password_verify()
                {
                    //password correct & user log in
                    // create user session variable
                    //$_SESSION["account_id"] = $account_id;
                    //$_SESSION["username"] = $username;
                    $this->_message["type"] = "success";
                    $this->_message["text"] = "password is correct";
                    
                    $this->_active = $active;
                    $this->_access_level = $user["access_level"];
                    $this->_internet_package = $user["internet_package"];
                    return true;
                } 
                else 
                {
                    $this->_message["type"] = "fail";
                    $this->_message["text"] = "password is incorrect";
                    return false;
                }
                
                //log user in
            } 
            else 
            {
                $this->_message["type"] = "fail";
                $this->_message["text"] = "account not exists";
                return false;
            }
        }
        $statement->close();
    }
    
    public function findUsername() 
    {
        $query = "select pk_id, username, email, password from accounts where username=? or email=?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("ss", $this->_username, $this->_username);
        if ($statement->execute()) 
        {
            $result = $statement->get_result();
            if ($result->num_rows > 0) 
            {
                $user = $result->fetch_assoc();
                $account_id = $user["pk_id"];
                $this->_message["type"] = "success";
                $this->_message["text"] = "password is correct";
                return $account_id;
            } 
            else 
            {
                $this->_message["type"] = "fail";
                $this->_message["text"] = "account not exists";
                return 0;
            }
        }
        $statement->close();
    }
    
    public function getAccountDetails($account_id)
    {
        $query = "select username, email, access_level, internet_package from accounts where pk_id = ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("i", $account_id);
        
        if ($statement->execute()) 
        {
            $result = $statement->get_result();
            if ($result->num_rows > 0)    // check number of rows in result
            {    
                $account_info = array();
                while ($row = $result->fetch_assoc()) 
                {
                    array_push($account_info, $row);
                }
                return $account_info;
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
    }
    
    public function createAccount()
    {
        //hash the password
        if ($this->_username == "")
        {
            $active = 1;  // users created via facebook authentication doesn't have to send verification email
        }
        else 
        {
            $active = 0;
        }
        $hashed = password_hash($this->_password, PASSWORD_DEFAULT);
        $query = "INSERT INTO accounts (username, email, password, active, first_name, last_name, 
                                        full_name, password_salt, password_reminder_token, 
                                        email_confirmation_token, access_level, internet_package, created_date, updated_date, last_login) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ' ', ' ', ' ', 2, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00','0000-00-00 00:00:00')";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("sssisss", $this->_username, $this->_email, $hashed, $active,
                               $this->_first_name, $this->_last_name, $this->_full_name);
        if ($statement->execute()) 
        {
            //account has been created
            $this->_message["type"] = "success";
            $this->_message["text"] = "Your account has been created.";
            $last_id = $statement->insert_id;
            return $last_id;;
        } 
        else 
        {
            if ($this->connection->errno == "1062") 
            {
                
                $dberror = $this->connection->error;  // either emial or username exists in database
                if (strstr($dberror, "email")) 
                {
                    $this->_message["errors_email"] = "email already exists";  //duplicate username
                } 
                elseif(strstr($dberror, "username")) 
                {   
                    $this->_message["errors_username"] = "email already exists";  // duplicate email
                }
            }
            $this->_message["type"] = "danger";
            $this->_message["text"] = "There has been an error.";
            return 0;
        }
    }
    
    public function checkUsername()
    {
        if (strlen($this->_username) < 6) // check username length
        {
            $this->_message["errors_username_length"] = "minimum 6 charcaters";
        }
        
        if (ctype_alnum($this->_username) == false) 
        {
            $this->_message["errors_username_alnum"] = "can only contain alphanumeric characters";
        }
        
        if (count($this->_message) > 0) 
        {
            return false;
        }
        return true;
    }
    
    public function checkEmail()
    {
        if (filter_var($this->_email, FILTER_VALIDATE_EMAIL) == false) 
        {
            $this->_messagee["errors_email"] = "invalid email address";
            return false;
        }    
        return true;
    }
    
    public function checkPassword()
    {
        if ($this->_password !== $this->_re_password) 
        {
            $this->_message["errors_password_equal"] = "passwords are not the same.";
        }
        
        if (strlen($this->_password) < 8 || strlen($this->_re_password) < 8) 
        {
            $this->_message["error_password_length"] = "minimum 8 characters";
        }
        
        if (count($this->_message) > 0) 
        {
            return false;
        }    
        return true;
    }
    
    public function emailVerificationLink($session_id)
    {
        $verify_token = bin2hex(openssl_random_pseudo_bytes(16));
		
        require_once "Mail.php";
        
        $from = "myWiFi Admin <parksiwan@gmail.com>";
        $to = $this->_email;
        $subject = "User Registration Activation Email";
        $this->updateActionAndToken($this->_username, 0, $verify_token);  // update token and active with 0
        $actual_link = "https://gateway-parksiwan.c9users.io/activate.php?sid=$session_id&token=$verify_token&user=$this->_username";
        $body = "Click this link to activate your account." . $actual_link;
        
        $host = "smtp.gmail.com";
        $port = 587;
        $username = "parksiwan";
        $password = "Park1101714";
        
        $headers = array ('From' => $from,
          'To' => $to,
          'Subject' => $subject);
        $smtp = Mail::factory('smtp',
          array ('host' => $host,
            'port' => $port,
            'auth' => true,
            'username' => $username,
            'password' => $password));
        
        $mail = $smtp->send($to, $headers, $body);
        
        if (PEAR::isError($mail)) 
        {
          echo("<p>" . $mail->getMessage() . "</p>");
        } 
        else 
        {
          echo("<p>You have registered and the activation mail is sent to your email. Click the activation link to activate you account.</p>");
        }
    }
    
    public function updateActionAndToken($active, $email_confirmation_token)
    {   
        $date = date('Y-m-d H:i:s');  //NOW();
        $query = "UPDATE accounts SET active = ?, updated_date = ?, email_confirmation_token=? WHERE username = ?";
        
        $statement = $this->connection->prepare($query);
        $statement->bind_param("isss", $active, $date, $email_confirmation_token, $this->_username);
        //echo $this->connection->error;
        if ($statement->execute()) 
        {
            //account has been created
            $this->_message["type"] = "success";
            $this->_message["text"] = "Your account has been updated.";
            $this->_active = $active;
            return true;
        } 
        else 
        {
            $this->_message["type"] = "danger";
            $this->_message["text"] = "Update error.";
            return false;
        }
    }
    
    public function updateLastlogin($id)
    {
        $date = date('Y-m-d H:i:s');  
        if ($id == 0)  
        {
            $query = "UPDATE accounts SET last_login = ? WHERE username = ?";
            $statement = $this->connection->prepare($query);
            $statement->bind_param("ss", $date, $this->_username);
        }
        else  // $id > 0 : social media authentication
        {
            $query = "UPDATE accounts SET last_login = ? WHERE pk_id = ?";
            $statement = $this->connection->prepare($query);
            $statement->bind_param("si", $date, $id); 
        }
        //echo $this->connection->error;
        if ($statement->execute()) 
        {
            //account has been created
            $this->_message["type"] = "success";
            $this->_message["text"] = "Your account has been updated.";
            return true;
        } 
        else 
        {
            $this->_message["type"] = "danger";
            $this->_message["text"] = "Update error.";
            return false;
        }   
    }
    
    public function updateUserProfile($access_level, $internet_package)
    {
        $date = date('Y-m-d H:i:s');  //NOW();
        $this->_access_level = $access_level;
        $this->_internet_package = $internet_package;
        $query = "UPDATE accounts SET email = ?, access_level = ?, internet_package = ? WHERE username = ?";
        
        $statement = $this->connection->prepare($query);
        $statement->bind_param("siis", $this->_email, $this->_access_level, $this->_internet_package, $this->_username);
        //echo $this->connection->error;
        if ($statement->execute()) 
        {
            //account has been created
            $this->_message["type"] = "success";
            $this->_message["text"] = "Your account has been updated.";
            return true;
        } 
        else 
        {
            $this->_message["type"] = "danger";
            $this->_message["text"] = "Update error.";
            return false;
        }
    }
    
    public function getMessage($index)
    {
        return $this->_message[$index];
    }
    
    public function getAllMessage()
    {
        return $this->_message;
    }
    
    public function getAccessLevel()
    {
        return $this->_access_level;
    }
    /*
    public function getErrors($index)
    {
        return $this->_errors[$index];
    }
    */
}
?>