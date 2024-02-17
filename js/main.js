function checkRegFunction() {
    var regBtn = document.getElementsByName("regButton")[0];
    var firstname = document.getElementsByName("fname").innerHTML;
    alert(firstname);
    var lastname = document.getElementsByName("lname").innerHTML;
    var dateTime = document.getElementsByName("dateTime").innerHTML;
    var email = document.getElementsByName("email").innerHTML;
    var password = document.getElementsByName("password").innerHTML;
    var regAns = document.getElementsByName("securityAns").innerHTML;
    if ((document.getElementsByName("fname").innerHTML || document.getElementsByName("lname").innerHTML || document.getElementsByName("dateTime").innerHTML || document.getElementsByName("email").innerHTML || document.getElementsByName("password").innerHTML || document.getElementsByName("securityAns").innerHTML) !== null) {
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