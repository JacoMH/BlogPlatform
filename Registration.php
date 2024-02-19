<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration | Blog Site</title>
    <link rel="stylesheet" type="text/css" href="css\mobile.css">
    <link rel="stylesheet" type="text/css" href="css\desktop.css" media="only screen and (min-width: 800px)">
</head>
<body>
    <main class="container">
        <?php
            include("Includes/header1.php");
            ?>
            <form id="logRegContainer" name="registration" onSubmit="return checkRegFunction()">
                First Name: <input type="text" name="fname">
                Surname: <input type="text" name="lname">
                DOB: <input type="date" name="dateTime">
                Email:  <input type="text" name="email">
                Password: <input type="password" name="password">
                Security Question: Where was your mother born?
                <input type="text" name="securityAns">
                <button type="submit" name="regButton"><i class="loginButton" ></i>Register</button>
            </form>
        <?php
            
        ?>
        <?php
            include("Includes/footer.php");
            ?>
    </main>
    <script src="js/main.js"></script>
</body>
</html>