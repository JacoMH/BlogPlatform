function checkRegFunction(status) {
    var fname = document.registration.fname.value;
    var surname = document.registration.lname.value;
    var DOB = document.registration.dateTime.value;
    var email = document.registration.email.value;
    var password = document.registration.password.value;
    var username = document.registration.username.value;
    var securityAns = document.registration.securityAns.value;
    if ((fname && surname && DOB && email && password && securityAns) !== "")  {
        alert("filled")
        
    }
    else{
        alert("not filled")
    }
}

function checkLogFunction() {
    var username = document.login.username.value;
    var password = document.login.password.value;
    var securityAns = document.login.securityAns.value;
    if ((username && password && securityAns) !== "") {
        alert("filled")
    }
    else{
        alert("not filled")
    }
}
function comment($postID) {
    alert($postID);
    window.location.assign("http://localhost/blogplatform/comments.php");
    document.comments.hi.innerHTML = $postID;
}

function importImage() {
    let input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/png, image/gif, image/jpeg';
    input.click();
}