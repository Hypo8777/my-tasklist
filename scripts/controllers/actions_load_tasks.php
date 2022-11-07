<?php

require "../model/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    class load_tasks extends DBConnection
    {

        private $user_id;
        private function get_task($user_id)
        {
            try {
                //code...
                $this->user_id = $user_id;
                $sess_id = $this->user_id;
                $connection = parent::connect();
                $query_load_tasks = "SELECT task_id,task FROM main_task WHERE `user_id`= ?";
                $_load_tasks = $connection->prepare($query_load_tasks);
                $_load_tasks->execute([$sess_id]);
                if ($_load_tasks->rowCount() !== 0) {
                    foreach ($_load_tasks->fetchAll() as $rows) {
?>
                        <option value="<?php echo $rows->task_id; ?>">
                            <?php echo $rows->task; ?>
                        </option>
                    <?php
                    }
                } else {
                    ?>
                    <option value="">No Main Tasks Found for the user</option>
<?php
                }
            } catch (PDOException $th) {
                //throw $th;
                die('ERROR : ' . $th->getMessage() . "<br>");
            }
        }
        public function task_list($sess_id)
        {
            $this->get_task($sess_id);
        }
    }

    $init = new load_tasks;
    $init->task_list($_SESSION['user_id']);
}
?>