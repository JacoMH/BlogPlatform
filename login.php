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
            include("Includes/HomeShortcut.php");
            ?>
    
    <div class="container">
        <form class="logRegContainer" name="login" onSubmit="checkLogFunction()" method="POST" action="login.php">
            Username: <input type="text" name="username">
            Password: <input type="password" name="password">
            Security Question: Where was your mother born?
            <input type="text" name="securityAns">
            <button type="submit" name="logButton"><i class="loginButton"></i>Login</button>
            <div class="newOrAlrUser">
                New User? <a href="Registration.php">Sign Up Here</a>
            </div>
        </form>
        <?php
            if (isset($_POST['logButton']) && $_POST['username'] != "" && $_POST["password"] != "" && $_POST["securityAns"] != "") {
                $username = $_POST["username"] ?? null; $username = preg_replace('/\s+/', '', $username);
                $password = $_POST["password"] ?? null; $password = preg_replace('/\s+/', '', $password);
                $securityAnswer = $_POST["securityAns"] ?? null; $securityAnswer = preg_replace('/\s+/', '', $securityAnswer);
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                $checkLoginQuery = "SELECT * FROM user WHERE username = '$username' AND securityQuestionAns = '$securityAnswer'";
                $result = mysqli_query($mysqli, $checkLoginQuery);
                while($row = mysqli_fetch_assoc($result)) {
                    $check_username = $row["username"];     //https://stackoverflow.com/questions/46819734/how-to-check-username-and-password-matches-the-database-values
                    $check_password = $row["hashedPass"];
                    $check_SecurityQ = $row["securityQuestionAns"];
                    $userID = $row['userID'];
                    $first_name = $row["firstName"];
                    $last_name = $row["lastName"];
                    $profilePic = $row["profilePicture"];
                    $bannerPic = $row["bannerPicture"];
                }
                if ($username == $check_username && $securityAnswer == $check_SecurityQ) {
                    $valid = password_verify ($password, $check_password);
                    if ($valid) {
                        session_start();
                        $_SESSION["userID"] = $userID;
                        $_SESSION["username"] = $check_username;
                        $_SESSION["firstName"] = $first_name;
                        $_SESSION["lastName"] = $last_name;
                        $_SESSION["profilePicture"] = $profilePic;
                        $_SESSION["bannerPicture"] = $bannerPic;
                        echo("<h2>logged in</h2>");
                        header("Location: Profile.php", true, 301);         
                    }
                    else{
                        echo("<h2>Incorrect credentials, Try again.</h2>");
                    }
                }
                else{
                    echo("no login");
                }
                //may change this as it does seem a bit pointless, could find a way to check if it exists in the database then move on.
                //find a way to if the login information is correct collect stuff and store it so you can go to the profile page and view stuff
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