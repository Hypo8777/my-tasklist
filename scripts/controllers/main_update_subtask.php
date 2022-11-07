<?php



require "../model/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {


    class update_subtask extends DBConnection
    {

        public function set_subtask($subid, $taskid)
        {
            try {
                //code...
                $connect = parent::connect();

                $check_if_not_finished = "SELECT sub_id,task_id,is_finished FROM sub_task WHERE sub_id = ? AND task_id = ?";
                $_if_not_finished = $connect->prepare($check_if_not_finished);
                $_if_not_finished->execute([$subid, $taskid]);
                foreach ($_if_not_finished->fetchAll() as $sub_rows) {
                    if ($sub_rows->is_finished !== 0) {
                        $query_update_subtask = "UPDATE sub_task SET is_finished = ? WHERE sub_id = ? AND task_id = ?";
                        $_update_subtask = $connect->prepare($query_update_subtask);
                        $_update_subtask->execute([0, $subid, $taskid]);
                        echo 0;
                    } else {
                        $query_update_subtask = "UPDATE sub_task SET is_finished = ? WHERE sub_id = ? AND task_id = ?";
                        $_update_subtask = $connect->prepare($query_update_subtask);
                        $_update_subtask->execute([1, $subid, $taskid]);
                        echo 1;
                    }
                }
            } catch (PDOException $th) {
                //throw $th;
                die('ERROR : ' . $th->getMessage() . "<br>");
            }
        }
    }
    $subid = $_POST['subid'];
    $task_id = $_POST['task_id'];
    $init = new update_subtask;
    $init->set_subtask($subid, $task_id);
} else {
    echo "No Posts Made!";
}
