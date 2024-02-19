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
            $status = "";
            include("Includes/header1.php");
            ?>
            <form class="logRegContainer" name="registration" onSubmit="checkRegFunction()" method="POST" action="Registration.php">
                First Name: <input type="text" name="fname">
                Surname: <input type="text" name="lname">
                DOB: <input type="date" name="dateTime">
                Email:  <input type="text" name="email">
                Username: <input type="text" name="username">
                Password: <input type="password" name="password">
                Security Question: Where was your mother born?
                <input type="text" name="securityAns">
                <button type="submit" name="regButton"><i class="loginButton" ></i>Register</button>                
            </form>
            <?php
                if (isset($_POST['regButton'])){
                    $first_name = $_POST["fname"] ?? null;
                    $last_name = $_POST["lname"] ?? null;
                    $dateTime = $_POST["dateTime"] ?? null;   //https://www.homeandlearn.co.uk/php/php4p7.html gave guidence on some holes in what i was doing
                    $email = $_POST["email"] ?? null;
                    $username = $_POST["username"] ?? null;
                    $password = $_POST["password"] ?? null;
                    $securityAnswer = $_POST["securityAns"] ?? null;
                }
            ?>
        <?php
            include("Includes/footer.php");
            ?>
    </main>
    <script src="js/main.js"></script>
</body>
</html>