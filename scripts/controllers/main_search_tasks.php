<?php


require "../model/connection.php";


class search_task extends DBConnection
{
    private $user_id;
    public $search;
    public function search($user_id, $search)
    {
        try {
            $this->user_id = $user_id;
            $find_id = $this->user_id;
            $this->search = $search;
            $find_items = $this->search;
            $connection = parent::connect();
            $query_task = "SELECT `user_id`,task_id,task,task_description,is_public,dateadded,datedue FROM main_task WHERE `user_id` = ? AND CONCAT(task_id,task,task_description,is_public,dateadded,datedue) LIKE ?";
            $_task = $connection->prepare($query_task);
            $_task->execute([$find_id, '%' . $find_items . '%']);
            if ($_task->rowCount() !== 0) {
                foreach ($_task->fetchAll() as $usertask) {
?>
                    <div class='task-card' id='task_card'>
                        <div class='task'>
                            <div class='task-title'>
                                <h5>Added : <?php echo $usertask->dateadded; ?> </h5>
                                <h5>Due : <?php echo $usertask->datedue; ?> </h5>
                            </div>
                            <div class='task-descr'>
                                <h1><?php echo $usertask->task; ?></h1>
                                <h6>Description</h6>
                                <p>
                                    <?php echo $usertask->task_description; ?>
                                </p>
                            </div>
                            <details class='task-sub'>
                                <?php
                                $check_sub = "SELECT task_id FROM sub_task WHERE task_id = '$usertask->task_id'";
                                $_sub = $connection->query($check_sub);
                                if ($_sub->rowCount() !== 0) {
                                ?>
                                    <summary>
                                        SubTasks
                                    </summary>
                                    <?php
                                    $query_subtask = "SELECT task_id,task,is_finished FROM sub_task WHERE task_id = ?";
                                    $_subtask = $connection->prepare($query_subtask);
                                    $_subtask->execute([$usertask->task_id]);
                                    foreach ($_subtask->fetchAll() as $subtask) {
                                    ?>
                                        <span>
                                            <input type='checkbox' name='' id=''>
                                            <label for=''><?php echo $subtask->task; ?></label>
                                        </span>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <summary>
                                        No Sub Task found
                                    </summary>
                                <?php
                                }
                                ?>
                            </details>

                        </div>
                        <div class='task-actions'>
                            <a href=''>Edit</a>
                            <a href=''>Finish</a>
                            <a href=''>Renew</a>
                            <a href=''>Delete</a>
                        </div>
                    </div>
<?php
                }
            } else {
                echo "No tasks found!";
            }
        } catch (PDOException $th) {
            die('ERROR : ' . $th->getMessage() . "<br>");
        }
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(isset($_SESSION['user_id'])){         
        $init = new search_task();
        $init->search($_SESSION['user_id'], $_POST['search']);
    }else{        
        echo "User is logged out!";
    }
}else{
    echo "No Search made";
}

