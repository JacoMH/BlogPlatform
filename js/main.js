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


function on() {
    document.getElementById("overlay").style.display = "block";
  }
  
  function off() {
    document.getElementById("overlay").style.display = "none";
  }

 
function refresh() {
    alert("hello");
    // Retrieve the sidebar element
let sidebar = document.querySelector(".sidebar");

// Retrieve the stored scroll position from localStorage
let storedScrollPosition = localStorage.getItem("sidebarScroll");
// If a stored scroll position exists, scroll the sidebar to that position
if (storedScrollPosition !== null) {
  sidebar.scrollTop = Number(storedScrollPosition);
}
// Store the scroll position in localStorage before the page is unloaded
window.addEventListener("beforeunload", () => {
  localStorage.setItem("sidebarScroll", sidebar.scrollTop);
});
}