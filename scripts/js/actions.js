
function getMainTasks() {
    $.ajax({
        type: "POST",
        url: "../scripts/controllers/actions_load_tasks.php",
        success: function (response) {
            $('#load_main_tasks').html(response);
        }
    });
}

getMainTasks();
var nav = window.location.search;
console.log(nav);
if (nav == "?create") {
    $('#form_create_task').show();
    $("#form_add_subtask").hide();
    $("#edit_delete_task").hide();
} else if (nav == "?subtask") {
    $('#form_create_task').hide();
    $("#form_add_subtask").show();
    $("#edit_delete_task").hide();
} else if (nav == "?delete") {
    $('#form_create_task').hide();
    $("#form_add_subtask").hide();
    $("#edit_delete_task").show();
} else {
    $('#form_create_task').show();
    $("#form_add_subtask").hide();
    $("#edit_delete_task").hide();
}

$('#form_create_task').on('submit', (e) => {
    e.preventDefault();
    let create_task = {
        create_taskName: $('#create_taskName').val(),
        create_taskDesc: $('#create_taskDesc').val(),
        create_taskDue: $('#create_taskDue').val(),
        create_isPublic: $('#create_isPublic').val()
    }
    $.ajax({
        type: "POST",
        url: "../scripts/controllers/actions_create_task.php",
        data: create_task,
        success: async (response) => {
            console.count(response);
            let responseData = JSON.parse(await response);
            if (responseData.response_status !== 0) {
                alert(responseData.msg);
            } else {
                alert(responseData.msg);
            }
        }
    });
    getMainTasks();
})


$('#form_add_subtask').on('submit', (e) => {
    e.preventDefault();
    let add_sub_task = {
        main_task_id: $('#load_main_tasks').val(),
        create_subTask: $('#create_subTask').val()
    }
    $.ajax({
        type: "POST",
        url: "../scripts/controllers/actions_add_subtasks.php",
        data: add_sub_task,
        success: async function (response) {
            console.log(await response);
            alert(await response);
        }
    });
});
