function checkRegFunction() {
    var regBtn = document.getElementsByName("regButton")[0];
    var firstname = document.getElementsByName("fname").innerHTML;
    alert(firstname);
    var lastname = document.getElementsByName("lname").innerHTML;
    var dateTime = document.getElementsByName("dateTime").innerHTML;
    var email = document.getElementsByName("email").innerHTML;
    var password = document.getElementsByName("password").innerHTML;
    var regAns = document.getElementsByName("securityAns").innerHTML;
    if ((firstname || lastname || dateTime || email || password || regAns) !== null) {
        alert("hello");
        regBtn.addEventListener("click", RegFunction)
    }
    else{
        alert("hola");
        document.getElementsByName("warning").innerHTML = "Incomplete Registration, try again.";
    }
}

function RegFunction(){
        
}