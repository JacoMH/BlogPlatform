<?php
    require_once('includes/config.php');
    session_start();
    
    //commenter details
    if ($_SESSION['username'] != "" && $_SESSION['userID'] != "") {
        $commenterUsername = $_SESSION['username'];
        $commenterUserID = $_SESSION['userID'];
        $commenterProfilePic = $_SESSION['profilePicture'];
    }
    else{
        header("Refresh:0; url=login.php");
    }


    if ($_GET['post']) {
        //fetch post recieving the comments
        $currentPost = $_GET['post'];
        $fetchPostQuery = mysqli_query($mysqli, "SELECT * FROM blogpost WHERE blogPostID = '$currentPost'");


    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comment | <?php echo($post);?></title>
</head>
<body>
    <?php
        While($postToCommentOn = mysqli_fetch_assoc($fetchPostQuery)) {
            $userWhoMadePostID = $postToCommentOn['userID'];
            //fetch user receiving the comments
            $commentedUserQuery = mysqli_query($mysqli, "SELECT * FROM user WHERE userID = '$userWhoMadePostID' ");

            While ($commentedUser = mysqli_fetch_assoc($commentedUserQuery)) {
                echo($commentedUser['username']);
              //  echo($commentedUserQuery['']);
            }
            echo("<div class = 'postContent'>");
                echo($postToCommentOn["blogPostText"]);
                echo($postToCommentOn['blogPostImage']);
                echo($postToCommentOn['blogPostLink']);
                echo($postToCommentOn['blogPostVideo']);
                echo("</div>");
        }


    ?>
</body>
</html>