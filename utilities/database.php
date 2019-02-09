<?php

require '../include/configurations.php';
class Database 
{
    private $connection;
    public function connect() 
    {
        $this->connection = new mysqli(HOST_NAME, USER_NAME, USER_PASSWORD, DB_NAME);
        $connectionError = mysqli_connect_errno();
        if ($connectionError) 
        {
            printf("Connection failed: %s", $connectionError);
            exit();
        }
        return true;
    }

    public function getData($sql) 
    {
        $result = mysqli_query($this->connection,$sql);
        if(!$result)
        {
            die('Invalid query: ' . mysqli_error($this->connection).'query').$sql;
        }
        return $result;
    }
    public function isUserExisted($email)
    {
        $query = "SELECT * FROM USER WHERE EMAIL ='$email'";
        $result = mysqli_query($this->connection, $query);

        if(($result ->num_rows) >0)
        {
            return True;
        }else
        {
           
            return False;
        }
             
    }
    public function storeUserDetails($firstName,$lastName,$emailAddress,$password)
    {
        $hashed_password = $this->hashing($password);
        $query = "INSERT INTO USER VALUE ('$firstName','$lastName','$emailAddress','$hashed_password')";
        $result = mysqli_query($this->connection, $query);
        if(!$result)
        {
            echo "errrooro";
            return False;
        }
        return True;
    }
  
    public function hashing($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    public function getUserData($email, $password) {
 
        $query = "SELECT * FROM USER WHERE EMAIL = '$email'";
        $result = mysqli_query($this->connection, $query);
        if ($result->num_rows > 0)
        {
            while ($row = $result->fetch_assoc()) 
            {
                if($this->checkingPassword($password, $row['PASSWORD'])) 
                {
                    echo "ok";
                    return True;
                }
                return FALSE;
            }
        } 
    }
    public function checkingPassword($password,$hashed_password)
    {
        return password_verify($password, $hashed_password);
    }

    public function insertData($sql)
    {
        $isItInserted = mysqli_query($this->connection,$sql);
        if(!$isItInserted)
        {
            print_r("failed to insert". $isItInserted);
        }
        return true;
    }
    
    public function __destruct() 
    {
        mysqli_close($this->connection)OR die("There was a problem disconnecting from the database.");
    }
}
