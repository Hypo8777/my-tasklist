
//? Removes any spaces made in all inputs
$('.inputs > input').on('input', function () {
    $(this).val($(this).val().replace(/\s+/g, ''));
});

$('.popup').hide();
$('.popup').removeClass('popup-success');
$('.popup').removeClass('popup-fail');


$('#signinform').on('submit', (e) => {
    e.preventDefault();
    let userinput_signin = {
        username: $('#inputusername').val(),
        password: $('#inputpassword').val()
    }
    $.ajax({
        type: "POST",
        url: "scripts/controllers/login.php",
        data: userinput_signin,
        success: async (response) => {
            console.count(response);
            var response = JSON.parse(response)
            $('.popup').show();
            if (await response.status !== 0) {
                $('.popup').text(await response.msg);
                $('.popup').addClass('popup-success');
                $('.popup').removeClass('popup-fail');
                window.location.replace(await response.goto);
            } else {
                $('.popup').text(await response.msg);
                $('.popup').removeClass('popup-success');
                $('.popup').addClass('popup-fail');
            }
        }
    });

});

$('#signupform').on('submit', (e) => {
    e.preventDefault();
    let signup_inputs = {
        username: $('#inputusernameC').val(),
        password: $('#inputpasswordC').val()
    }
    $.ajax({
        type: "POST",
        url: "scripts/controllers/create_user.php",
        data: signup_inputs,
        success: async (response) => {
            console.count(response);
            var response = JSON.parse(response);
            $('.popup').show();
            if (await response.status !== 0) {
                $('.popup').text(await response.msg);
                $('.popup').addClass('popup-success');
                $('.popup').removeClass('popup-fail');
            } else {
                $('.popup').text(await response.msg);
                $('.popup').removeClass('popup-success');
                $('.popup').addClass('popup-fail');
            }
        }
    });
});


