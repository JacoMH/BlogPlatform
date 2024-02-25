
<?php
    require_once('includes/config.php');
    session_start();
    $SessionUser = $_SESSION['userID'];
    
    
    //display posts made by user
    $userPostQuery = "SELECT * FROM blogpost WHERE userID = '$SessionUser'";
    $result = mysqli_query($mysqli, $userPostQuery);
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
            include("Includes/header2.php");
        ?>
        <div class="banner">
            
        </div>
        <?php
        echo("Welcome back, "); echo($_SESSION["firstName"]);
        ?>
        <div class="profilePic">
            
        </div>
        <div class="realName">
            <?php
            echo($_SESSION["firstName"]); echo(" "); echo($_SESSION["lastName"]); 
            ?>
        </div> 
        <span class="username">
            <?php
            echo($_SESSION["username"]);
            ?>
        </span>
        <div class="AllPostsContainer">
            <?php
            //do if statements depending on the type of post it is, e.g. if its just text then do a just text post, with images has a diff format etc.
            while ($row = mysqli_fetch_assoc($result)) {
                $profileID = $row['userID'];
                
                //fetch their profile
                $fetchUserProfile = "SELECT profilePicture, username FROM user where userID = '$profileID'";

                //profile pic
                echo("<div class = 'userProfilePic'>");
                $profileResult = mysqli_query($mysqli, $fetchUserProfile);
                $profile = mysqli_fetch_assoc($profileResult);
                echo($profile["profilePicture"]);
                echo("</div>");

                //username
                echo("<div class = 'username'>");
                    
                echo("</div>");
                
                //fetch the post
                if ($row['blogPostText'] != "" && $row['blogPostImage'] == "" && $row['blogPostLink'] == "" && $row['blogPostVideo'] == ""){
                    echo("<div class = 'postContent'>");
                    echo($row["blogPostText"]);
                    echo("</div>");
                }
                else if ($row['blogPostText'] != "" && $row['blogPostImage'] != "" && $row['blogPostLink'] == "" && $row['blogPostVideo'] == "") {
                    echo("<div class = 'postContent'>");
                    echo($row["blogPostText"]);
                    echo($row['blogPostImage']);
                    echo("</div>");
                }
                else if($row['blogPostText'] != "" && $row['blogPostImage'] != "" && $row['blogPostLink'] != "" && $row['blogPostVideo'] == "") {
                    echo("<div class = 'postContent'>");
                    echo($row["blogPostText"]);
                    echo($row['blogPostImage']);
                    echo($row['blogPostLink']);
                    echo("</div>");
                }
                else if($row['blogPostText'] != "" && $row['blogPostImage'] != "" && $row['blogPostLink'] != "" && $row['blogPostVideo'] != "") {
                    echo("<div class = 'postContent'>");
                    echo($row["blogPostText"]);
                    echo($row['blogPostImage']);
                    echo($row['blogPostLink']);
                    echo($row['blogPostVideo']);
                    echo("</div>");
                }
                else if($row['blogPostText'] != "" && $row['blogPostImage'] == "" && $row['blogPostLink'] != "" && $row['blogPostVideo'] != "") {
                    echo("<div class = 'postContent'>");
                    echo($row["blogPostText"]);
                    echo($row['blogPostLink']);
                    echo($row['blogPostVideo']);
                    echo("</div>");
                }
                else if($row['blogPostText'] != "" && $row['blogPostImage'] == "" && $row['blogPostLink'] == "" && $row['blogPostVideo'] != "") {
                    echo("<div class = 'postContent'>");
                    echo($row["blogPostText"]);
                    echo($row['blogPostVideo']);
                    echo("</div>");
                }
            }
            ?>
        </div>
        <?php
                //query which selects all profile stuff, lists posts etc. made. If query returns nothing then 2 buttons are displayed telling the user to log in or reg
                include("Includes/footer.php");
        ?>
    </main>
</body>
</html>