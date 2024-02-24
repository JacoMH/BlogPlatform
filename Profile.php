
<?php
    require_once('includes/config.php');
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css\mobile.css">
    <link rel="stylesheet" type="text/css" href="css\desktop.css" media="only screen and (min-width: 800px)">
    <title>Profile | <?php$_SESSION["username"]?></title>
</head>
<body>
    hello
    <?php
            //query which selects all profile stuff, lists posts etc. made. If query returns nothing then 2 buttons are displayed telling the user to log in or reg
    ?>
</body>
</html>