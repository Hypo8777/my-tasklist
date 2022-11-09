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
            $query_task = "SELECT `user_id`,task_id,task,task_description,is_public,is_finished,dateadded,datedue FROM main_task WHERE `user_id` = ?";
            $_task = $connection->prepare($query_task);
            $_task->execute([$find_id]);
            foreach ($_task->fetchAll() as $usertask) {
?>
                <div class='task-card' id='task_card'>
                    <div class='task'>
                        <div class='task-title'>
                            <?php
                            if ($usertask->datedue  >= date("Y-m-d")) {
                            ?>
                                <h5>Added : <?php echo $usertask->dateadded; ?> </h5>
                                <?php
                                if ($usertask->is_finished !== 1) {
                                ?>
                                    <p style="color:#fff; background: darkgreen; padding: .3em; border-radius:.5em;">ONGOING</p>
                                <?php
                                } else {
                                ?>
                                    <p style="color:#fff; background: #79ff79; padding: .3em; border-radius:.5em;">FINISHED</p>
                                <?php
                                }
                                ?>
                                <h5>Due : <?php echo $usertask->datedue; ?> </h5>
                            <?php
                            } else {
                            ?>
                                <h5>Added : <?php echo $usertask->dateadded; ?> </h5>
                                <p style="color:#fff; background: crimson; padding: .3em; border-radius:.5em;">Task Missed</p>
                                <h5>Due : <?php echo $usertask->datedue; ?> </h5>
                            <?php
                            }
                            ?>
                        </div>
                        <div class='task-name'>
                            <h1>{ <?php echo $usertask->task; ?> }</h1>
                        </div>
                        <h4>Task Description</h4>
                        <div class="task-descr">
                            <p>
                                <?php echo $usertask->task_description; ?>
                            </p>
                        </div>
                        <h4>Todo</h4>
                        <details class='task-sub' open>
                            <summary>
                                Sub Tasks
                            </summary>
                            <?php
                            $query_check_subtasks = "SELECT sub_id,task_id,task,is_finished,timestamp FROM sub_task WHERE task_id = '$usertask->task_id'";
                            $_check_subtasks = $connection->query($query_check_subtasks);
                            foreach ($_check_subtasks->fetchAll() as $sub_rows) {
                                $sub_stat = "";
                                if ($sub_rows->is_finished == 1) {
                                    $sub_stat .= "checked";
                                } else {
                                    $sub_stat .= "";
                                }
                            ?>
                                <div class="sub-div">
                                    <table>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="<?php echo $sub_rows->task_id; ?>" id="<?php echo $sub_rows->sub_id; ?>" onchange="upsubtask(this.id,this.name)" <?php echo $sub_stat; ?>>
                                            </td>
                                            <td>
                                                <label for="<?php echo $sub_rows->sub_id; ?>"><?php
                                                                                                echo $sub_rows->task; ?></label>
                                            </td>
                                            <td><a onclick="delete_sub(this.id,this.name)" name="<?php echo $sub_rows->task_id; ?>" id="<?php echo $sub_rows->sub_id; ?>">Delete Sub</a></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <p>Last Updated : <?php echo $sub_rows->timestamp; ?></p>
                                            </td>
                                        </tr>
                                        <hr>
                                    </table>

                                </div>
                            <?php
                            }
                            ?>


                        </details>

                    </div>
                    <div class='task-actions'>
                        <button onclick="taskActionFinish(this.id,this.name)" id="<?php echo $usertask->task_id; ?>" name="<?php echo $usertask->user_id; ?>">Finish Task</button>
                        <button onclick="taskActionDelete(this.id,this.name)" id="<?php echo $usertask->task_id; ?>" name="<?php echo $usertask->user_id; ?>">Delete Task</button>
                    </div>
                </div>
                <?php
            }
        } catch (PDOException $th) {
            die('ERROR : ' . $th->getMessage() . "<br>");
        }
    }


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
                                <?php
                                if ($usertask->datedue  >= date("Y-m-d")) {
                                ?>
                                    <h5>Added : <?php echo $usertask->dateadded; ?> </h5>
                                    <p style="color:#fff; background: #79ff79; padding: .3em; border-radius:.5em;">Ongoing</p>
                                    <h5>Due : <?php echo $usertask->datedue; ?> </h5>
                                <?php
                                } else {
                                ?>
                                    <h5>Added : <?php echo $usertask->dateadded; ?> </h5>
                                    <p style="color:#fff; background: crimson; padding: .3em; border-radius:.5em;">Task Missed</p>
                                    <h5>Due : <?php echo $usertask->datedue; ?> </h5>
                                <?php
                                }
                                ?>
                            </div>
                            <div class='task-name'>
                                <h1>{ <?php echo $usertask->task; ?> }</h1>
                            </div>
                            <h4>Task Description</h4>
                            <div class="task-descr">
                                <p>
                                    <?php echo $usertask->task_description; ?>
                                </p>
                            </div>
                            <h4>Todo</h4>
                            <details class='task-sub' open>
                                <summary>
                                    Sub Tasks
                                </summary>
                                <?php
                                $query_check_subtasks = "SELECT sub_id,task_id,task,is_finished,timestamp FROM sub_task WHERE task_id = '$usertask->task_id'";
                                $_check_subtasks = $connection->query($query_check_subtasks);
                                foreach ($_check_subtasks->fetchAll() as $sub_rows) {
                                    $sub_stat = "";
                                    if ($sub_rows->is_finished == 1) {
                                        $sub_stat .= "checked";
                                    } else {
                                        $sub_stat .= "";
                                    }
                                ?>
                                    <div class="sub-div">
                                        <table>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="<?php echo $sub_rows->task_id; ?>" id="<?php echo $sub_rows->sub_id; ?>" onchange="upsubtask(this.id,this.name)" <?php echo $sub_stat; ?>>
                                                </td>
                                                <td>
                                                    <label for="<?php echo $sub_rows->sub_id; ?>"><?php
                                                                                                    echo $sub_rows->task; ?></label>
                                                </td>
                                                <td><a onclick="delete_sub(this.id,this.name)" name="<?php echo $sub_rows->task_id; ?>" id="<?php echo $sub_rows->sub_id; ?>">Delete Sub</a></td>

                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <p>Last Updated : <?php echo $sub_rows->timestamp; ?></p>
                                                </td>
                                            </tr>
                                            <hr>
                                        </table>

                                    </div>
                                <?php
                                }
                                ?>


                            </details>

                        </div>
                        <div class='task-actions'>
                            <button onclick="taskActionFinish(this.id,this.name)" id="<?php echo $usertask->task_id; ?>" name="<?php echo $usertask->user_id; ?>">Finish Task</button>
                            <button onclick="taskActionDelete(this.id,this.name)" id="<?php echo $usertask->task_id; ?>" name="<?php echo $usertask->user_id; ?>">Delete Task</button>
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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $init = new loadTasks();
    if (isset($_POST['search'])) {
        $init->search($_SESSION['user_id'], $_POST['search']);
    } else {
        $init->load_task($_SESSION['user_id']);
    }
} else {
    echo "No POST made";
}
