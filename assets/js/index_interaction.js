// Sign In
var showpassword = document.getElementById('showpassword');
showpassword.addEventListener('change', () => {
    var inputpassword = document.getElementById('inputpassword');
    var showpassword_lbl = document.getElementById('showpassword_lbl');
    if (inputpassword.type == "password") {
        inputpassword.type = "text";
        showpassword_lbl.innerText = "Hide Password";
    } else {
        inputpassword.type = "password";
        showpassword_lbl.innerText = "Show Password";
    }
})

// Sign Up
var showpasswordC = document.getElementById('showpasswordC');
showpasswordC.addEventListener('change', () => {
    var inputpasswordC = document.getElementById('inputpasswordC');
    var showpassword_lblC = document.getElementById('showpassword_lbl');
    if (inputpasswordC.type == "password") {
        inputpasswordC.type = "text";
        showpassword_lblC.innerText = "Hide Password";
    } else {
        inputpasswordC.type = "password";
        showpassword_lblC.innerText = "Show Password";
    }
})




