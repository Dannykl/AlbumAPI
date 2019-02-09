<?php
include('../utilities/database.php');
ini_set('display_errors', 1);
$db = new Database();
$db->connect();
$response = array("error" => FALSE);

if(isset($_POST['firstName'])&& isset($_POST['lastName'])&&isset($_POST['email'])&&isset($_POST['password']))
{   $firstName = $_POST['firstName'];
    $lastName  = $_POST['lastName'];
    $emailAddress = $_POST['email'];
    $password = $_POST['password'];
    //check if the user is already in db
    //store their data if they are not in db
    if($db->isUserExisted($emailAddress))
    {   $response["error"]=TRUE;
        $response["error_msg"] = $emailAddress." is already existed";
        echo json_encode($response);
    }
    else{
        $user = $db->storeUserDetails($firstName,$lastName,$emailAddress,$password);
        if(!$user)
        {   $response["error"] = TRUE;
            $response["error_msg"] = "Unknown error occurred in registration!";
            echo json_encode($response);
        }else{
            $response["error"] = FALSE;
            $response["user"]["first_name"] = $firstName;
            $response["user"]["last_name"] = $lastName;
            $response["user"]["email_address"] = $emailAddress;
            echo json_encode($response);
        }
    }
}else
{
    $response["error"] = TRUE;
    $response["error_msg"] = "All fields must be filled";
    echo json_encode($response);
}
?>