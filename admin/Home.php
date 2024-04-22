<?php
    session_start();

    //checks if user is admin
    if ($_SESSION['jobTitle'] != 'Admin') {
        echo "<script> window.location.href='Home.php'</script>";
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css\mobile.css">
    <link rel="stylesheet" type="text/css" href="css\desktop.css" media="only screen and (min-width: 800px)">
    <title>Admin | Home</title>
</head>
<body>
    <?php
        include("xampp\htdocs\BlogPlatform\includes\adminShortcut.php");
        include("includes/profileShortcut.php");
    ?>
</body>
</html>