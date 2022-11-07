$('#newpassword').hide();
$('#popup').hide();
$('#submitnewuser').hide();

$('#forgotform').on('submit', (e) => {
    e.preventDefault();
});

$('#submituser').click(() => {
    let sendusertoken = {
        myusername: $('#myusername').val(),
        mytoken: $('#mytoken').val()
    }
    $.ajax({
        type: "POST",
        url: "../scripts/controllers/verify_user.php",
        data: sendusertoken,
        success: function (response) {
            $('#popup').show();
            console.log(response);
            var response = JSON.parse(response);
            if (response.status !== 0) {
                $('#popup').text(response.msg);
                $('#popup').addClass('popup-success');
                $('#popup').removeClass('popup-fail');

                $('#newpassword').show();
                $('#submitnewuser').show();
                $('#submituser').hide();
            } else {
                $('#popup').text(response.msg);
                $('#popup').removeClass('popup-success');
                $('#popup').addClass('popup-fail');

                $('#newpassword').hide();
                $('#submitnewuser').hide();
                $('#submituser').show();
            }
        }
    });
})

$('#submitnewuser').click(() => {
    if ($("#password_final").val() !== $('#password_first').val()) {
        $('#popup').text("Password does not match!");
        $('#popup').removeClass('popup-success');
        $('#popup').addClass('popup-fail');
    } else {
        let changepassword = {
            myusername: $('#myusername').val(),
            mytoken: $('#mytoken').val(),
            mypassword: $('#password_final').val()
        }
        $.ajax({
            type: "POST",
            url: "../scripts/controllers/change_password.php",
            data: changepassword,
            // dataType: "dataType",
            success: function (response) {
                var response = JSON.parse(response);
                if (response.status !== 0) {
                    $('#popup').text(response.msg);
                    $('#popup').addClass('popup-success');
                    $('#popup').removeClass('popup-fail');

                    $('#newpassword').show();
                    $('#submitnewuser').show();
                    $('#submituser').hide();
                } else {
                    $('#popup').text(response.msg);
                    $('#popup').removeClass('popup-success');
                    $('#popup').addClass('popup-fail');

                    $('#newpassword').hide();
                    $('#submitnewuser').hide();
                    $('#submituser').show();
                }
            }
        });
    }
});