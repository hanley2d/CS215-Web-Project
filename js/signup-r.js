document.getElementById("signup").addEventListener("submit", signUpForm, false);

function signUpForm(event) {        
    var valid = true;
    var elements = event.currentTarget;
    
    var uname = elements[0].value; //Username
    var email = elements[1].value; //Email
    var dob = elements[2].value;    // DOB
    var avatar = elements[3].value; //avatar
    var psswd = elements[4].value; //Password
    var cpsswd = elements[5].value; //Confirm Password    

    var regex_email = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/;
    var regex_uname = /^[a-zA-Z0-9_-]+$/;
    var regex_pswd  = /^(\S*)?\d+(\S*)?$/;

    var textNode;

    var msg_uname = document.getElementById("msg_uname");
    var msg_email = document.getElementById("msg_email");
    var msg_dob = document.getElementById("msg_dob");
    var msg_ava = document.getElementById("msg_ava");
    var msg_pswd = document.getElementById("msg_pswd");
    var msg_pswdr = document.getElementById("msg_pswdr");
    msg_email.innerHTML = "";
    msg_uname.innerHTML = "";
    msg_dob.innerHTML = "";
    msg_ava.innerHTML = "";
    msg_pswd.innerHTML = "";
    msg_pswdr.innerHTML = "";
    
    // check username
    if (uname == null || uname == "") {
        textNode = document.createTextNode("Username is empty.");
        msg_uname.appendChild(textNode);
        valid = false;     
    }

    else if (regex_uname.test(uname) == false) {
        textNode = document.createTextNode("Username is invalid. No symbols or spaces.");
        msg_uname.appendChild(textNode);
        valid = false;         
    }   
    // check email
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
    else if (email.length > 60) {
        textNode = document.createTextNode("Email address too long. Maximum is 60 characters.");
        msg_email.appendChild(textNode);
        valid = false;       
    }

    // check DOB
    if (dob == null || dob == "") {
        textNode = document.createTextNode("Please enter DOB.");
        msg_dob.appendChild(textNode);
        valid = false;
    }
    // check avatar
    if (avatar == null || avatar == "") {
        textNode = document.createTextNode("Please select an avatar image.");
        msg_ava.appendChild(textNode);
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
        textNode = document.createTextNode("Invalid password. Password must contain at least one digit.");
        msg_pswd.appendChild(textNode);
        valid = false;
    }

    // check confirm password
    if (cpsswd == null || cpsswd == "") {
        textNode = document.createTextNode("Confirm password is empty. ");
        msg_pswdr.appendChild(textNode);
        valid = false;
      }
    
      if (cpsswd != psswd) {
        textNode = document.createTextNode("Passwords do not match.");
        msg_pswdr.appendChild(textNode);
        valid = false;
      }

    // if form is valid
    var display_info = document.getElementById("display_info");
    display_info.innerHTML = "";
    if (valid == true) {        
        event.preventDefault(); // test if valid
        //Set green text color indicated everything is OK
        display_info.style.color = "lime"; // Style Method 1: manipulate style directly
        display_info.innerHTML = "valid";         
    }
    // if form is not valid
    else {
        event.preventDefault();
        display_info.style.color = "orange";
        display_info.innerHTML = "invalid";
    }
}