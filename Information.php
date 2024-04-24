<?php
    require_once('includes/config.php');
    session_start();
  //   checks if user role is user or moderator
     if ($_SESSION['jobTitle'] == 'Admin') {
        echo "<script> window.location.href='Home.php'</script>";
    }

    $SessionUser = $_SESSION['userID'];


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css\mobile.css">
    <link rel="stylesheet" type="text/css" href="css\desktop.css" media="only screen and (min-width: 800px)">
    <title>Profile | Change Password</title>
</head>
<body>
    <main class='container'>
<?php
    if (!empty($_SESSION['username'])) {
        require_once('includes/profileShortcut.php');
        $SessionUser = $_SESSION['userID'];
        $_SESSION['on'] = "yes";
    }
    ?>
        <form method='POST' class = 'logRegContainer' action=''>
            Enter New Password:
            <input name = 'passwordChanger1' type='password'></input>
            Confirm New Password:
            <input name = 'passwordChanger2' type='password'></input>
            <button type='submit' name='changePassConfirm' >Change Password</button>
        </form>
        
        <?php
        if (isset($_POST['changePassConfirm'])) {
            if ($_POST['passwordChanger1'] != "" || $_POST['passwordChanger2'] != "") {
                $pass1 = $_POST['passwordChanger1'];
                $pass2 = $_POST['passwordChanger2'];
                if ($pass1 == $pass2) {
                    $hash = password_hash($pass1, PASSWORD_DEFAULT);
                    $updatePasswordQuery = mysqli_query($mysqli, "UPDATE user SET hashedPass = '$hash'");
                    echo("Password Changed.");
                }
            }
            else{
                echo("<span style='align-self: center;'>Incorrect Input.</span>");
            }
        }
        ?>
           <?php
        include("Includes/footer.php");
        ?>
    </main>
    
</body>
</html>