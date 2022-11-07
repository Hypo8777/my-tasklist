<?php

require "../model/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    class Task extends DBConnection
    {
        private $taskName;
        private $taskDesc;
        private $isPublic;
        private $taskAdded;
        private $taskDue;

        public function set_task(
            $create_taskName,
            $create_taskDesc,
            $create_isPublic,
            $create_taskAdd,
            $create_taskDue
        ) {

            $input = [
                $_SESSION['user_id'],
                $this->taskName  = $create_taskName,
                $this->taskDesc  = $create_taskDesc,
                $this->isPublic  = $create_isPublic,
                $this->taskAdded  = $create_taskAdd,
                $this->taskDue  = $create_taskDue
            ];
            $connection = parent::connect();
            try {
                //code...
                $query_create_task = "INSERT INTO main_task (`user_id`,task,task_description,is_public,dateadded,datedue) VALUES(?,?,?,?,?,?)";
                $_create_task = $connection->prepare($query_create_task);
                $_create_task->execute($input);
                echo json_encode([
                    'status_response' => 1,
                    'msg' => "Task Added Succesfully!"
                ]);
            } catch (PDOException $th) {
                die('ERROR : ' . $th->getMessage() . "<br>");
            }
        }
    }

    // User Inputs
    $create_taskName = $_POST['create_taskName'];
    $create_taskDesc = $_POST['create_taskDesc'];
    $create_isPublic = $_POST['create_isPublic'];
    $create_taskAdd = date('Y-m-d');
    $create_taskDue = $_POST['create_taskDue'];

    $init = new Task;
    $init->set_task(
        $create_taskName,
        $create_taskDesc,
        $create_isPublic,
        $create_taskAdd,
        $create_taskDue
    );
} else {
    echo "No Posts Made!";
}
