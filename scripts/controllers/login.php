<?php

// Parent class directory
require  "../model/connection.php";


// class that extends from the parent that handles the connection to the database
class user_login extends DBConnection
{
    private $usernameLogin;
    protected $passwordLogin;

    // Sanitizes user input (basic)
    function sanitize($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    private function set_login($usernameLogin, $passwordLogin)
    {
        try {
            //code... 

            // The user inputs
            $in = [
                $this->usernameLogin = $this->sanitize($usernameLogin),
                $this->passwordLogin = $this->sanitize($passwordLogin)
            ];

            // The Connection when querying in the database tables
            $connection = parent::connect();

            // A Query when User Attempts to login
            $query_login = "SELECT `user_id`,username,`password` FROM users WHERE username = ? AND `password` = ?";
            $_login = $connection->prepare($query_login);
            $_login->execute($in);
            if ($_login->rowCount() !== 0) {
                // When user is found in the database table
                foreach ($_login->fetchAll() as $fetchRes) {
                    // A query that checks the user status as presented in the database table 
                    $query_check_user_state = "SELECT `user_id`,is_active FROM users WHERE `user_id` = ? AND is_active = ?";

                    $_check_user_state = $connection->prepare($query_check_user_state);
                    // If the user state is 1 means the user was not logged out propery
                    $_check_user_state->execute([$fetchRes->user_id, 1]);

                    if ($_check_user_state->rowCount() !== 0) {
                        // Proceeds to log the user out
                        $query_logout = "UPDATE users SET is_active = ? WHERE `user_id` = ?";
                        $_logout = $connection->prepare($query_logout);
                        $_logout->execute([0, $fetchRes->user_id]);
                        echo json_encode([
                            'status' => 0,
                            'msg' => 'Please re-login as your account was not logged out properly!'
                        ]);
                    } else {
                        // If none of the above is met then proceeds the user to log in
                        $query_update_status = "UPDATE users SET is_active = ? WHERE `user_id` = ?";
                        $_update_status = $connection->prepare($query_update_status);
                        $_update_status->execute([1, $fetchRes->user_id]);
                        // These sessions is what we are going to user to create new task and other stuff exclusive to the user
                        $_SESSION['username'] = $fetchRes->username;
                        $_SESSION['user_id'] = $fetchRes->user_id;
                        // prints a json format array for the client js to receive
                        echo json_encode([
                            'status' => 1,
                            'user_id' => $fetchRes->user_id,
                            'user_status' => 1,
                            'msg' => "User Found",
                            'goto' => 'views/main.html'
                        ]);
                    }
                }
            } else {
                // When user is not found in the database table returns an error message
                echo json_encode([
                    'status' => 0,
                    'msg' => "User Not Found"
                ]);
            }

            // Close Connection
            $query_login = null;
            $_login = null;
            $connection = null;
        } catch (PDOException $th) {
            //throw $th;
            die('ERROR : ' . $th->getMessage() . "<br>");
        }
    }

    public function login($input_user, $input_pass)
    {
        $this->set_login($input_user, $input_pass);
    }
}

//TODO Initiate when user logs in
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $initiate_login = new user_login;
    $user = $_POST['username'];
    $pass = $_POST['password'];
    //? Input goes inside -> login('here');
    $initiate_login->login($user, $pass);
} else {
    echo "No Posts Made";
}
