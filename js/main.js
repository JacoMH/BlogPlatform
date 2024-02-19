function checkRegFunction() {
    var fname = document.registration.fname.value;
    var surname = document.registration.lname.value;
    var DOB = document.registration.dateTime.value;
    var email = document.registration.email.value;
    var password = document.registration.password.value;
    var securityAns = document.registration.securityAns.value;
    if ((fname && surname && DOB && email && password && securityAns) !== "")  {
        alert("filled")
        return true;
    }
    else{
        alert("not filled")
        return false;
    }
}


function RegFunction(){
        
}