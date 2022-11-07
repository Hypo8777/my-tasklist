// Logs out if userstatus has been changed from active(1) to inactive(0)
setInterval(() => {
    $.ajax({
        type: "POST",
        url: "../scripts/controllers/main_user_status.php",
        success: async (response) => {
            console.log(response);
            let data = JSON.parse(response);
            if (await data.status == 0) {
                $('body').hide();
                alert(await data.msg);
                window.location.replace(await data.goto);
            }
        }
    });
}, 10000);



$.ajax({
    type: "POST",
    url: "../scripts/controllers/main_user_status.php",
    success: async (response) => {
        console.log(response);
        let data = JSON.parse(response);
        if (await data.status == 0) {
            $('body').hide();
            alert(await data.msg);
            window.location.replace(await data.goto);
        }
    }
});