<?php

require "../model/connection.php";

// Extends the class from the parent Class named DBConnection  dir = "../model/connection.php"
class userStatus extends DBConnection
{
    // Userid as property
    private $user_id;
    // Function for constructing reading user state
    private function set_user_status($user_id)
    {
        try {
            // Setting the property value using the inputs stored within the session when logging in
            $this->user_id = $user_id;
            $user = $this->user_id;
            $connection = parent::connect();
            // if the user did not log in the the user will not be able to access the main page
            if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_status'])) {
                echo json_encode([
                    'status' => 0,
                    'msg' => 'You were not logged in!',
                    'goto' => '../'
                ]);
            } else {
                // Check the user status in interval via requests
                $query_check_user_state = "SELECT `user_id`,is_active FROM users WHERE `user_id` = ? AND is_active =?";
                $_check_user_state = $connection->prepare($query_check_user_state);
                $_check_user_state->execute([$user, 0]);
                // if the user did not logged out
                if ($_check_user_state->rowCount() !== 0) {
                    echo json_encode([
                        'status' => 0,
                        'msg' => 'You did not logout properly please login again!',
                        'goto' => '../'
                    ]);
                } else { 
                    echo json_encode([
                        'status' => 1
                    ]);
                }
            }
        } catch (PDOException $th) { 
            die('ERROR : ' . $th->getMessage() . "<br>");
        }
    }
    // use for accessing the class method when determining the user to be logged out or not
    public function userstate($user_id)
    {
        $this->set_user_status($user_id);
    }
}

// access this file only when it receives a request from the client side to be processed by the server side
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // The User mus log in in order to proceed to the main page
    if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_status'])) {
        echo json_encode([
            'status' => 0,
            'msg' => 'You were not logged in!',
            'goto' => '../'
        ]);
    } else {
        $init = new userStatus;
        // $_SESSION['user_id'] the stored session of the user id when logging in
        $init->userstate($_SESSION['user_id']); // 
    }
} else {
    die('Nope! You Cant');
}
