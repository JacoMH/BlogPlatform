<?php
    require_once('includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login | Blog Platform</title>
    <link rel="stylesheet" type="text/css" href="css\mobile.css">
    <link rel="stylesheet" type="text/css" href="css\desktop.css" media="only screen and (min-width: 800px)">
</head>
<body>
    <main class="container">
        <?php
            include("Includes/header1.php");
            ?>
    
    <div class="logRegContainer">
        <form class="logRegContainer" name="login" onSubmit="checkLogFunction()" method="POST" action="login.php">
            Username: <input type="text" name="username">
            Password: <input type="password" name="password">
            Security Question: Where was your mother born?
            <input type="text" name="securityAns">
            <button type="submit" name="logButton"><i class="loginButton"></i>Login</button>
        </form>
        <?php
            if (isset($_POST['logButton']) && $_POST['username'] != "" && $_POST["password"] != "" && $_POST["securityAns"] != "") {
                $username = $_POST["username"] ?? null; $username = preg_replace('/\s+/', '', $username);
                $password = $_POST["password"] ?? null; $password = preg_replace('/\s+/', '', $password);
                $securityAnswer = $_POST["securityAns"] ?? null; $securityAnswer = preg_replace('/\s+/', '', $securityAnswer);
                $checkLoginQuery = "SELECT EXISTS(username, pass, securityQuestionAns FROM user WHERE username = '$username' AND pass = '$password' AND securityQuestionAns = '$securityAnswer')";
                $result = $mysqli->query($checkLoginQuery);
                $row = $result->fetch_array(MYSQLI_ASSOC);              //learnt how to put the object into strings so i can compare to what the user has entered here: https://www.php.net/manual/en/mysqli-result.fetch-array.php
                if ($row["username"])
                //may change this as it does seem a bit pointless, could find a way to check if it exists in the database then move on.
            
            }
        ?>
    </div>

        <?php
            include("Includes/footer.php");
            ?>
    </main>
    <script src="js/main.js"></script>
</body>
</html>