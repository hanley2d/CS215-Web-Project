var charCount = document.getElementById("textbox");
charCount.addEventListener("input", characterCount);


function characterCount(event) {      
    var elements = event.currentTarget;    
    var textbox = elements[0].value;  
       
    var counter = (256 - textbox.length);
    var remaining = document.getElementById("display_info");
    
    remaining.textContent = counter + "/256";
    if (counter < 0) {        
        display_info.style.color = "red";                     
    }     
    else {
        display_info.style.color = "#dbd8d2";
    }       
    
    charCount.addEventListener("submit", validate);
    function validate(event) {
        var err_msg = document.getElementById("display_info2");
        err_msg.innerHTML = ""; 
        if (counter < 0) {
            event.preventDefault();                                 
            err_msg.innerHTML = "too many characters";   
        }    
      
    }   
}   



