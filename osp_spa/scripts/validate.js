let valid_user = false;
let valid_pass = false;
let valid_phone = false;
let valid_email = false;
let matching_pass = false;

function validate() {
    if (valid_user && valid_pass && valid_phone && valid_email && matching_pass) {
        document.getElementById("submit-btn").innerHTML = "<button type=\"submit\" class=\"enable\">Sign Up</button>";
    }
    else {
        document.getElementById("submit-btn").innerHTML = "<button type=\"submit\" class=\"disable\" disabled>Sign Up</button>";
    }
}

function chk_user() {
    let username = document.getElementById('username').value;
    let len = username.length;
    if (len > 30) { 
        document.getElementById('username_msg').innerHTML = "Username must be at most 30 characters.";
        valid_user = false;
    }
    else if (len < 1) {
        document.getElementById('username_msg').innerHTML = "This field is cannot be empty";
        valid_user = false;
    }
    else {
        document.getElementById('username_msg').innerHTML = "";
        valid_user = true;
    }

    validate();
    return 0;
}

function chk_email() {
    //regex for valid email
    const email_reg = /^[\w\.\-\+]+@[\w\-\.]+\.[A-Za-z]{2,63}$/;
    let email = document.getElementById('email').value;
    if (email.length == 0) {
        document.getElementById('email_msg').innerHTML = "This field is cannot be empty";
        valid_email = false;
    }
    //valid email
    else if (email.match(email_reg)) {    
        document.getElementById('email_msg').innerHTML = "";
        valid_email = true;
    }
    //invalid email
    else {  
        document.getElementById('email_msg').innerHTML = "Please enter a valid email";
        valid_email = false;
    }

    validate();
    return 0;
}

function chk_phone() {
    const phone_reg = /^[0-9]{3}-[0-9]{3}-[0-9]{4}$/;
    let phone = document.getElementById('phone_number').value;
    if (phone.length == 0) {
        document.getElementById('phone_msg').innerHTML = "This field is cannot be empty";
        valid_phone = false;
    }
    //valid phone
    else if (phone.match(phone_reg)) {    
        document.getElementById('phone_msg').innerHTML = "";
        valid_phone = true;
    }
    //invalid phone
    else {  
        document.getElementById('phone_msg').innerHTML = "Please enter a valid phone number";
        valid_phone = false;
    }

    validate();
    return 0;
}

function chk_pass() {
    let password = document.getElementById('password').value;
    let len = password.length;
    if (len < 1) {
        document.getElementById('password_msg').innerHTML = "This field cannot be empty"
        valid_pass = false;
    }
    else if (len < 6) {
        document.getElementById('password_msg').innerHTML = "Password too short (must be at least 6 characters)"
        valid_pass = false;
    }
    else if (len > 255) {
        document.getElementById('password_msg').innerHTML = "Password too long (must be at most 255 characters)"
        valid_pass = false;
    }
    else {
        document.getElementById('password_msg').innerHTML = ""
        valid_pass = true;
    }

    validate();
    return 0;
}

function match_pass() {
    let password = document.getElementById('password').value;
    let check_pass = document.getElementById('confirm_password').value;
    if (password != check_pass) {
        document.getElementById('confirmpass_msg').innerHTML = "Passwords do not match";
        matching_pass = false;
    }
    else {
        document.getElementById('confirmpass_msg').innerHTML = "";
        matching_pass = true;
    }

    validate();
    return 0;
}


