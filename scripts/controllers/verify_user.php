<?php



require  "../model/connection.php";



class verify_user extends DBConnection
{
    private $ver_username;
    protected $ver_token;

    private function set_ver($ver_username, $ver_token)
    {

        try {
            //code...
            $in = [
                $this->ver_username = $ver_username,
                $this->ver_token  = $ver_token
            ];
    
            $connection = parent::connect();
            $query_check_user_token = "SELECT username,token FROM users WHERE username = ? AND token = ?";
            $_check_user_token = $connection->prepare($query_check_user_token);
            $_check_user_token->execute($in);
            if ($_check_user_token->rowCount() !== 0) {
                echo json_encode([
                    'status' => 1,
                    'msg' => "User Verified!"
                ]);
            } else {
                echo json_encode([
                    'status' => 0,
                    'msg' => "Invalid Username or Token"
                ]);
            }
    
            $connection = null;
            $query_check_user_token = null;
            $_check_user_token = null;
        } catch (PDOException $th) {
            //throw $th;
            die('ERROR : ' . $th->getMessage() . "<br>");
        }
        
    }

    public function verify($u, $t)
    {
        $this->set_ver($u, $t);
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $initiate_verify = new verify_user;
    $user = $_POST['myusername'];
    $token = $_POST['mytoken'];
    $initiate_verify->verify(
        $user,
        $token
    );
}else{
    echo "No Posts Made";
}
