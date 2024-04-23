<?php
    session_start();
    require_once('../includes/config.php');
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
    <link rel="stylesheet" type="text/css" href="..\css\mobile.css">
    <link rel="stylesheet" type="text/css" href="..\css\desktop.css" media="only screen and (min-width: 800px)">
    <title>Admin | Latest</title>
</head>
<body>
    <main class='container'>

    <?php
    include("../includes/adminShortcut.php");
    ?>
    </main>
</body>
</html>