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
        <div class="logRegContainer">
        <table>
            <tr>
                <th>First name</th>
                <th><input  class = "search" type="text" name="fname" placeholder="Enter.."></th>
            </tr>
            <tr>
                <th>Last name</th>
                <th><input  class = "search" type="text" name="fname" placeholder="Enter.."></th>
            </tr>
            <tr>
                <th>Date Of Birth</th>
                <th><input type= "date"></th>
            </tr>
            <tr>
                <th>Email</th>
                <th><input class = "search" type="email" name="email" placeholder="Enter.."></th>
            </tr>

            <tr>
                <th>Password</th>
                <th><input  class = "search" type="password" name="password" placeholder="Enter.."></th>
            </tr>
        </table>
        
        <table>
            <tr>
            <tr style="display: flex; flex-direction: column; justify-content: center; margin: 15px 0px;">
                <th>Security Question: Where were your parents born?:</th>
                <th><input  class = "search" type="text" placeholder="Enter.."></th>
            </tr>
            </tr>
        </table>

        <table>
            <tr>
            <button type="submit"><i class="loginButton"></i>Register</button>
            </tr>
        </table>
        </div>
        <?php
            include("Includes/footer.php");
            ?>
    </main>
</body>
</html>