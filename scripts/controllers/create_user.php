<?php
require  "../model/connection.php";


class user_create extends DBConnection
{
    private $usernameCreate;
    protected $passwordCreate;

    function sanitize($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    private function set_create($usernameCreate, $passwordCreate)
    {
        try {
            //code... 

            $is_active = 0;
            $user_id = strtoupper(substr(md5($usernameCreate),0,8)) . strtoupper(substr(uniqid(),6,13));
            $token = md5($user_id . uniqid());

            $in = [
                $user_id,
                $this->usernameCreate = $this->sanitize($usernameCreate),
                $this->passwordCreate = $this->sanitize($passwordCreate),
                $token,
                $is_active
            ];

            $connection = parent::connect();

            $query_check_user = "SELECT username FROM users WHERE username = ?";
            $_check_user = $connection->prepare($query_check_user);
            $_check_user->execute([$usernameCreate]);
            if ($_check_user->rowCount() !== 0) {
                echo json_encode([
                    'status' => 0,
                    'msg' => "Username Taken"
                ]);
            } else {
                $query_create = "INSERT INTO users(`user_id`,username,`password`,token,is_active) VALUES(?,?,?,?,?)";
                $_create = $connection->prepare($query_create);
                $_create->execute($in);

                echo json_encode([
                    'status' => 1,
                    'msg' => "User Creation Success!"
                ]);
            }
            // Close Connection 
            $connection         = null;
            $query_check_user   = null;
            $_check_user        = null;
            $query_create       = null;
            $_create            = null;
        } catch (PDOException $th) {
            //throw $th;
            die('ERROR : ' . $th->getMessage() . "<br>");
        }
    }

    public function create($input_user, $input_pass)
    {
        $this->set_create($input_user, $input_pass);
    }
}

// Initiate when user creates an account
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $initiate_create = new user_create;
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $initiate_create->create($user, $pass);
}else{
    echo "No Posts Made";
}
