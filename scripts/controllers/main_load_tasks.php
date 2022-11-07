<?php


require "../model/connection.php";


class loadTasks extends DBConnection
{
    private $user_id;
    public function load_task($user_id)
    {
        try {
            $this->user_id = $user_id;
            $find_id = $this->user_id;
            $connection = parent::connect();
            $query_task = "SELECT `user_id`,task_id,task,task_description,is_public,dateadded,datedue FROM main_task WHERE `user_id` = ?";
            $_task = $connection->prepare($query_task);
            $_task->execute([$find_id]);
            foreach ($_task->fetchAll() as $usertask) {
?>
                <div class='task-card' id='task_card'>
                    <div class='task'>
                        <div class='task-title'>
                            <?php
                            if ($usertask->datedue  >= $usertask->dateadded) {
                            ?>
                                <h5>Added : <?php echo $usertask->dateadded; ?> </h5>
                                <p>Ongoing</p>
                                <h5>Due : <?php echo $usertask->datedue; ?> </h5>
                            <?php
                            } else {
                            ?>
                                <h5>Added : <?php echo $usertask->dateadded; ?> </h5>
                                <p>Task Missed</p>
                                <h5>Due : <?php echo $usertask->datedue; ?> </h5>
                            <?php
                            }
                            ?>
                        </div>
                        <div class='task-name'>
                            <h1>{ <?php echo $usertask->task; ?> }</h1>
                        </div>
                        <div class="task-descr">
                            <h5>Task Description</h5>
                            <p>
                                <?php echo $usertask->task_description; ?>
                            </p>
                        </div>
                        <details class='task-sub' open>
                            <?php
                            $check_sub = "SELECT task_id FROM sub_task WHERE task_id = '$usertask->task_id'";
                            $_sub = $connection->query($check_sub);
                            if ($_sub->rowCount() !== 0) {
                            ?>
                                <summary>
                                    SubTasks
                                </summary>
                                <div class="sub-div">
                                    <?php
                                    $query_subtask = "SELECT task_id,sub_id,task,is_finished,timestamp FROM sub_task WHERE task_id = ?";
                                    $_subtask = $connection->prepare($query_subtask);
                                    $_subtask->execute([$usertask->task_id]);
                                    foreach ($_subtask->fetchAll() as $subtask) {
                                    ?>
                                        <div class="sub-div-item">
                                            <?php
                                            if ($subtask->is_finished == 1) {
                                                echo '<input type="checkbox" name="' . $subtask->task_id . '"  onchange="upsubtask(this.id,this.name)" id="' .  $subtask->sub_id . '" checked>';
                                            } else {
                                                echo '<input type="checkbox" name="' . $subtask->task_id . '"  onchange="upsubtask(this.id,this.name)" id="' .  $subtask->sub_id . '">';
                                            }
                                            echo '<label for="' .  $subtask->sub_id . '">' . $subtask->task . '</label';
                                            ?>
                                        </div>
                                        <div class="sub-div-item">
                                            <h5>Last Updated : <?php echo $subtask->timestamp; ?></h5>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            <?php
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
        } catch (PDOException $th) {
            die('ERROR : ' . $th->getMessage() . "<br>");
        }
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['user_id'])) {
        $init = new loadTasks();
        $init->load_task($_SESSION['user_id']);
    } else {
        echo "User is logged out!";
    }
} else {
    echo "No POST made";
}
