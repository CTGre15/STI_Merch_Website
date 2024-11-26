function openRegister(){
    document.getElementById("loginform").style.display = "none";
    document.getElementById("registerform").style.display = "block";
}

function openLogin(){
    document.getElementById("registerform").style.display = "none";
    document.getElementById("loginform").style.display = "block";
}

function showLoginPassword(){
    var x = document.getElementById("loginpassword");
    if(x.type === "password"){
        x.type = "text";
    } else {
        x.type = "password";
    }
}

function showRegisterPassword(){
    var x = document.getElementById("registerpassword");
    if(x.type === "password"){
        x.type = "text";
    } else {
        x.type = "password";
    }
}