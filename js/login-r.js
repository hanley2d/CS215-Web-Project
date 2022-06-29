document.getElementById("login").addEventListener("submit", loginForm, false);

function loginForm(event) {
    var valid = true;
    var elements = event.currentTarget;

    var email = elements[0].value;
    var psswd = elements[1].value;

    var regex_email = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/;
    var regex_pswd  = /^\S*$/;

    var textNode;

    var msg_email = document.getElementById("msg_email");
    var msg_pswd = document.getElementById("msg_pswd");
    msg_email.innerHTML = "";
    msg_pswd.innerHTML = "";

    if (email == null || email == "") {
        textNode = document.createTextNode("Email address empty.");
        msg_email.appendChild(textNode);
        valid = false;
    }
    else if (regex_email.test(email) == false) {
        textNode = document.createTextNode("Email address is invalid. Example: username@somewhere.sth");
        msg_email.appendChild(textNode);
        valid = false;
    }

     // check password
     if (psswd == null || psswd == "") {
        textNode = document.createTextNode("Password is empty.");
        msg_pswd.appendChild(textNode);
        valid = false;
    }
    
    else if (psswd.length < 6) {
        textNode = document.createTextNode("Password must be at least 6 characters.");
        msg_pswd.appendChild(textNode);
        valid = false;
    }
    
    else if (regex_pswd.test(psswd) == false) {
        textNode = document.createTextNode("Invalid password. No spaces.");
        msg_pswd.appendChild(textNode);
        valid = false;
    }

    // if form is valid
    // var display_info = document.getElementById("display_info");
    // display_info.innerHTML = "";
    // if (valid == true) {        
    //     event.preventDefault(); // test if valid
    //     //Set green text color indicated everything is OK
    //     display_info.style.color = "lime"; // Style Method 1: manipulate style directly
    //     display_info.innerHTML = "valid";         
    // }
    // // if form is not valid
    // else {
    //     event.preventDefault();
    //     display_info.style.color = "orange";
    //     display_info.innerHTML = "invalid";
    // }
}