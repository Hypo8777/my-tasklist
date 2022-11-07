<?php

require  "../model/connection.php";


class user_changePass extends DBConnection
{
    protected $newpassword;
    private $username;
    protected $token;

    private function set_change_pass(
        $newpassword,
        $username,
        $token
    ) {
        try {
            $connection = parent::connect();

            $in_check = [
                $this->username = $username,
                $this->token = $token
            ];

            $query_check_user_token = "SELECT username,token FROM users WHERE username = ? AND token = ?";
            $_check_user_token = $connection->prepare($query_check_user_token);
            $_check_user_token->execute($in_check);
            if ($_check_user_token->rowCount() !== 0) {

                $in_set = [
                    $this->newpassword = $newpassword,
                    $this->username = $username,
                    $this->token = $token
                ];

                $query_change_password = "UPDATE users SET `password` = ? WHERE username = ? AND token = ?";
                $_change_password = $connection->prepare($query_change_password);
                $_change_password->execute($in_set);

                echo json_encode([
                    'status' => 1,
                    'msg' => "Password Change Success!"
                ]);
                
            } else {

                echo json_encode([
                    'status' => 0,
                    'msg' => "Invalid Username or Token"
                ]);
            }

            $connection = null;
            $query_change_password = null;
            $_change_password = null;
        } catch (PDOException $th) {
            die('ERROR : ' . $th->getMessage() . "<br>");
        }
    }

    public function change_password($newpass, $user, $token)
    {
        $this->set_change_pass($newpass, $user, $token);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $initiate_change = new user_changePass;
    $username = $_POST['myusername'];
    $token = $_POST['mytoken'];
    $newpass = $_POST['mypassword'];
    $initiate_change->change_password(
        $newpass,
        $username,
        $token
    );
}else{
    echo "No Posts made";
}
