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
        <table>
            <tr>
                <th>Username</th>
                <th><input  class = "search" type="text" name="fname" placeholder="Enter.."></th>
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
            <button type="submit"><i class="loginButton"></i>Login</button>
            </tr>
        </table>
    </div>

        <?php
            include("Includes/footer.php");
            ?>
    </main>
</body>
</html>