<?php


require "../model/connection.php";



class main_task_actions extends DBConnection
{

    public function delete_sub_task($sub_id, $task_id)
    {
        try {
            //code...
            $conn = parent::connect();

            $query_delete_sub_task = "DELETE FROM sub_task WHERE sub_id = ? AND task_id = ?";
            $_delete_sub_task = $conn->prepare($query_delete_sub_task);
            $_delete_sub_task->execute([$sub_id, $task_id]);
            echo "Sub Deleted!";
        } catch (PDOException $th) {
            //throw $th;
            die('ERROR : ' . $th->getMessage() . "<br>");
        }
    }

    public function finish_task($task_id, $user_id)
    {
        $conn = parent::connect();
        $query_finish_task = "UPDATE main_task SET is_finished = ? WHERE task_id = ? AND `user_id` = ?";
        $_finish_task = $conn->prepare($query_finish_task);
        $_finish_task->execute([1, $task_id, $user_id]);
        echo "Task FINISHED";
    }

    public function delete_task($task_id, $user_id)
    {
        try {
            //code...
            $conn = parent::connect();
            $query_delete_task = "DELETE FROM main_task WHERE task_id = ? AND `user_id` = ?";
            $_delete_task = $conn->prepare($query_delete_task);
            if ($_delete_task->execute([$task_id, $user_id])) {
                $query_delete_sub_task = "DELETE FROM sub_task WHEREtask_id = ?";
                $_delete_sub_task = $conn->prepare($query_delete_sub_task);
                $_delete_sub_task->execute([$task_id]);
            }
            echo "TASK Deleted!";
        } catch (PDOException $th) {
            //throw $th;
            die('ERROR : ' . $th->getMessage() . "<br>");
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $init_action = new main_task_actions;
    $subid_post = $_POST['sub_id'];
    $taskid_post = $_POST['task_id'];
    $userid_post = $_POST['user_id'];
    if (isset($_POST['delete_subtask'])) {
        $init_action->delete_sub_task($subid_post, $taskid_post);
    } else if (isset($_POST['finish_task'])) {
        $init_action->finish_task(
            $taskid_post,
            $userid_post
        );
    } else if (isset($_POST['delete_task'])) {
        $init_action->delete_task(
            $taskid_post,
            $userid_post
        );
    }
} else {
    echo "REQUESTING USER POST!";
}
