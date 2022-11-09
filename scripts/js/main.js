function load_tasks() {
    $.ajax({
        type: "POST",
        url: "../scripts/controllers/main_load_tasks.php",
        data: {
            load: "load"
        },
        success: async (response) => {
            await $('#content_task').html(response);
        }
    });
}

load_tasks();



function upsubtask(subid, taskid) {
    $.ajax({
        type: "POST",
        url: "../scripts/controllers/main_update_subtask.php",
        data: {
            subid: subid,
            task_id: taskid
        },
        success: function (response) {
            load_tasks();
        }
    });
}


// Deletes Seleted Sub task
function delete_sub(subid, taskid) {
    $.ajax({
        type: "POST",
        url: "../scripts/controllers/main_task_actions.php",
        data: {
            delete_subtask: "delete_subtask",
            sub_id: subid,
            task_id: taskid
        },
        success: () => {
            load_tasks();
        }
    });
}

function taskActionFinish(taskid, userid) {
    $.ajax({
        type: "POST",
        url: "../scripts/controllers/main_task_actions.php",
        data: {
            finish_task: "finish_task",
            task_id: taskid,
            user_id: userid
        },
        success: () => {
            load_tasks();
        }
    });
}
function taskActionDelete(taskid, userid) {
    $.ajax({
        type: "POST",
        url: "../scripts/controllers/main_task_actions.php",
        data: {
            delete_task: "delete_task",
            task_id: taskid,
            user_id: userid
        },
        success: () => {
            alert("Task Deleted Succesfully");
            load_tasks();
        }
    });
}


$('#newtask').click(() => {
    window.location.href = ("actions.html");
});



$('#btnsearchtask').click(() => {
    $.ajax({
        type: "POST",
        url: "../scripts/controllers/main_load_tasks.php",
        data: {

            search: document.getElementById('findtask').value
        },
        success: async (response) => {
            await $('#content_task').html(response);
        }
    });
});

$('#btnLogout').click(() => {
    $.ajax({
        type: "POST",
        url: "../scripts/controllers/main_logout.php",
        success: async (response) => {
            var response = JSON.parse(response);
            if (await response.status == 1) {
                window.location.replace(response.goto);
            } else {
                alert('User Must be logged in to logout');
            }
        }
    });
});
