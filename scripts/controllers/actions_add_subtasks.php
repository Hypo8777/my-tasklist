<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    require "../model/connection.php";

    class add_subtask extends DBConnection
    {
        private $main_task_id;
        private $sub_task;

        private function set_sub_task($main_task_id, $sub_task)
        {
            try {
                //code...
                $input = [
                    $this->$main_task_id = $main_task_id,
                    $this->$sub_task = $sub_task
                ];
                $connection = parent::connect();
                $query_add_subtask = "INSERT INTO sub_task(task_id,task) VALUES (?,?)";
                $_add_subtask = $connection->prepare($query_add_subtask);
                $_add_subtask->execute($input);
                echo "Sub Task Added Succesfully!";
            } catch (PDOException $th) {
                //throw $th;
                die('ERROR : ' . $th->getMessage() . "<br>");
            }
        }
        public function addSubTask($main_task_id, $sub_task)
        {
            $this->set_sub_task($main_task_id, $sub_task);
        }
    }

    $main_task_id = $_POST['main_task_id'];
    $create_subTask = $_POST['create_subTask'];

    $init = new add_subtask;
    $init->addSubTask(
        $main_task_id,
        $create_subTask
    );
} else {
    echo "NO POSTS MADE!";
}
