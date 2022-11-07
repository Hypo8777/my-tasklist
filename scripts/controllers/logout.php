<?php

require "../model/connection.php";

class destroy_sesstion extends  DBConnection
{
    private $user_id;
    private function set_user_logout($user_id)
    {
        try {
            $this->user_id = $user_id;
            $logout_id = $this->user_id;
            $connection = parent::connect();
            $query_logout = "UPDATE users SET is_active = ? WHERE `user_id` = ?";
            $_logout = $connection->prepare($query_logout);
            $_logout->execute([0, $logout_id]);
            header("location: ../../");
        } catch (PDOException $th) {
            die('ERROR : ' . $th->getMessage() . "<br>");
        }
    }
    public function logout($user_id)
    {
        $this->set_user_logout($user_id);
    }
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['logout'])) {
        $init = new destroy_sesstion;
        $init->logout($_SESSION['user_id']);
    } else {
        echo "Do Something!";
    }
} else {
    echo "Do Something!";
}
