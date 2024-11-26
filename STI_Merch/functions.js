function openModal(modalId) {
    document.getElementById(modalId).style.display = "block";
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = "none";
    }
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