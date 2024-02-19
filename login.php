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
        <form class="logRegContainer" name="login" onSubmit="checkLogFunction()">
            Username: <input type="text" name="username">
            Password: <input type="password" name="password">
            Security Question: Where was your mother born?
            <input type="text" name="securityAns">
            <button type="submit" name="logButton"><i class="loginButton"></i>Login</button>
        </form>
    </div>

        <?php
            include("Includes/footer.php");
            ?>
    </main>
    <script src="js/main.js"></script>
</body>
</html>