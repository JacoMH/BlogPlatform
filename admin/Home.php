<?php
    session_start();
    require_once('../includes/config.php');
    //checks if user is admin
    if ($_SESSION['jobTitle'] != 'Admin') {
        echo "<script> window.location.href='../Home.php'</script>";
    }
    if ($_SESSION['jobTitle'] == 'user') {
        echo "<script> window.location.href='../Home.php'</script>";
    }

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="..\css\mobile.css">
    <link rel="stylesheet" type="text/css" href="..\css\desktop.css" media="only screen and (min-width: 800px)">
    <title>Admin | Home</title>
</head>
<body>
    <main class='container'>
        <!-- header -->
        <header style= "display: flex; flex-direction: row; justify-content: space-between;">
            <div id="logo"><a href="../Home.php"><h2>Blog Platform</h2></a></div> 
            <div class="profileShortcut">
            <div style="align-content: center; padding-right: 20px; height: 20px; border-radius: 8px; padding: 10px; margin: 5px; background: green;"><a class = "profileShortcut" href="../logout.php">Log Out</a></div>
                <div style="">
                <div ><a href="Home.php"><img class= 'profilePhoto' src=<?php echo("../{$_SESSION['profilePicture']}"); ?> alt = "Profile Picture"></a></div>
                <span style="display: flex; justify-content: center;"><?php echo($_SESSION['username']); ?></span>
                </div>
            </div>
        </header>

        <h2>Users</h2>
        <?php
            $SelectUsersQuery = mysqli_query($mysqli, "SELECT * FROM user WHERE jobTitle = 'user'");
        ?>
            <div class='profilesContainer'>
                <?php
                        while($Users = mysqli_fetch_assoc($SelectUsersQuery)) {
                            //userID
                            $userID = $Users['userID'];

                            echo("<div class='profile'>");
                            echo("<a href='../user.php?user={$Users['userID']}'><img class='userPhoto' src='../{$Users['profilePicture']}'></a>");
                            echo("<span class='username' style='display: flex; justify-content: center; font-size: large;'>{$Users['username']}</span>");
                             
                            //delete button
                            echo("<form class='deleteButton'  style='padding: 3px; align-self: center;' method='POST' action='Profile.php'>");
                            echo("<button type = 'submit' name = 'delete$userID'>delete</button>");
                            echo("</form>");
                            echo("</div>");

                                //delete user
                            if (isset($_POST["delete$userID"])) {
                                $deleteUser = mysqli_query($mysqli, "DELETE * FROM user WHERE userID = $userID");
                            }
                        }
                ?>
            </div>
    </main>
</body>
</html>