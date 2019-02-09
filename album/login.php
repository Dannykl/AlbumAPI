<?php

include('../utilities/database.php');
ini_set('display_errors', 1);
$db = new Database();
$db->connect();

// json response array
$response = array("error" => FALSE);
if(isset($_POST['email']) && isset($_POST['password']))
{
// receiving the post params
    $email = $_POST['email'];
    $password = $_POST['password'];
 
    // get the user by email and password
    $user = $db->getUserData($email, $password);
 
    if ($user == True) {
        // use is found
        $response["error"] = FALSE;
        $response["user"]["email"] = $email;
        echo json_encode($response);
    } else if($user == False){
        //password is not correct
        $response["error"] = TRUE;
        $response["error_msg"] = "Login credentials are wrong. Please try again!";
        
    }else{
        // email was not found is not found with the credentials
        $response["error"] = TRUE;
        $response["error_msg"] = "Email was not found! Try sign-up";
        echo json_encode($response);
    }
} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters email or password is missing!";
    echo json_encode($response);
}

?>

