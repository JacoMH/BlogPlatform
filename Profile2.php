<?php
    session_start();
    if(empty($_SESSION['username'])) {
        header("refresh:0; url='login.php'");
    }
    require_once('includes/config.php');
    $SessionUser = $_SESSION['userID'];

    //fetch total profile likes
    $fetchUserInfo = mysqli_query($mysqli, "SELECT * FROM user WHERE userID = '$SessionUser'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css\mobile.css">
    <link rel="stylesheet" type="text/css" href="css\desktop.css" media="only screen and (min-width: 800px)">
    <title>Profile | <?php echo($_SESSION["username"])?></title>
</head>
<body>
    <main class="container">
            <?php
                include("Includes/HomeShortcut.php");

                include("topOfProfile.php");
            ?>


 
            

    </main>
</body>
</html>