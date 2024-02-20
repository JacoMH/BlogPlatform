<?php
    require_once('includes/config.php');
?>

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
                if (isset($_POST['regButton']) && $_POST["fname"] != "" && $_POST["lname"] != "" && $_POST["dateTime"] != null && $_POST["email"] != null && $_POST["username"] != null && $_POST["password"] != null && $_POST["securityAns"] != null) {
                    $first_name = $_POST["fname"] ?? null; $first_name = preg_replace('/\s+/', '', $first_name);
                    $last_name = $_POST["lname"] ?? null; $last_name = preg_replace('/\s+/', '', $last_name);
                    $dateTime = $_POST["dateTime"] ?? null; $dateTime = preg_replace('/\s+/', '', $dateTime);   //https://www.homeandlearn.co.uk/php/php4p7.html gave guidence on some holes in what i was doing
                    $email = $_POST["email"] ?? null; $email = preg_replace('/\s+/', '', $email);
                    $username = $_POST["username"] ?? null; $username = preg_replace('/\s+/', '', $username);
                    $password = $_POST["password"] ?? null; $password = preg_replace('/\s+/', '', $password);
                    $securityAnswer = $_POST["securityAns"] ?? null; $securityAnswer = preg_replace('/\s+/', '', $securityAnswer);
                    print($first_name);
                    $send = $mysqli->prepare( "INSERT INTO user (username, firstName, lastName, DOB, email, pass, securityQuestionAns) VALUES ('$username', '$first_name', '$last_name', '$dateTime', '$email', '$password', '$securityAnswer')");
                    $send->execute();
                    header("Location: login.php", true, 301);
                }   
            ?>
        <?php
            include("Includes/footer.php");
            ?>
    </main>
    <script src="js/main.js"></script>
</body>
</html>