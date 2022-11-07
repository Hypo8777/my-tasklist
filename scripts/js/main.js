
$('#newtask').click(() => {
    window.location.href = ("actions.html");
});



$('#btnsearchtask').click(() => {
    $.ajax({
        type: "POST",
        url: "../scripts/controllers/main_search_tasks.php",
        data: {
            search: document.getElementById('findtask').value
        },
        success: async (response) => {
            await $('#content_task').html(response);
        }
    });
});


function load_tasks() {
    $.ajax({
        type: "POST",
        url: "../scripts/controllers/main_load_tasks.php",
        success: async (response) => {
            await $('#content_task').html(response);
        }
    });
}

load_tasks();


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



function upsubtask(subid, taskid) {
    $.ajax({
        type: "POST",
        url: "../scripts/controllers/main_update_subtask.php",
        data: {
            subid: subid,
            task_id: taskid
        },
        success: function (response) {
            console.count(response);
        }
    });
}