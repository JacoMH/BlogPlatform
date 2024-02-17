function checkRegFunction() {
    if ((Registration.getElementByName("fname") || Registration.getElementByName("lname") || Registration.getElementByName("dateTime") || Registration.getElementByName("email") || Registration.getElementByNmae("password") || Registration.getElementById("securityAns")) != Null) {
        Registration.getElementById("regButton").addEventListener("click", RegFunction)
    }
    else{
        Registration.getElementById("warning").innerHTML = "Incomplete Registration, try again.";
    }
}

function RegFunction(){
        
}