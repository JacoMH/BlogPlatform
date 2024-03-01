<?php
    require_once('includes/config.php');
    session_start();
    
    if ($_GET['post']) {
        //fetch post
        $currentPost = $_GET['post'];
        $fetchPost = "SELECT * FROM blogpost WHERE blogPostID = '$currentPost'";
        $postResult = mysqli_query($mysqli, $fetchPost);
        $row =  mysqli_fetch_assoc($postResult);

        $userOfPost = $row['userID'];
        //fetch user profile
        $fetchProfile = "SELECT username, profilePicture FROM user WHERE userID = '$userOfPost'";
        $profileResult = mysqli_query($mysqli, $fetchProfile);
        $profile = mysqli_fetch_assoc($profileResult);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>hello</title>
    <link rel="stylesheet" type="text/css" href="css\mobile.css">
    <link rel="stylesheet" type="text/css" href="css\desktop.css" media="only screen and (min-width: 800px)">
</head>
<body>
    <div class="AllPostsContainer">
        <?php
            echo("<div class = 'userProfilePic'>");
            echo($profile["profilePicture"]);
            echo($profile["username"]);
            echo("</div>");
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

        //comment section

    ?>
    </div>
    <script src="js/main.js"></script>
</body>
</html>