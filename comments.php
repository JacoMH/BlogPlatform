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
        //fetch post receiving the comments
        $currentPost = $_GET['post'];
        $fetchPost = "SELECT * FROM blogpost WHERE blogPostID = '$currentPost'";
        $postResult = mysqli_query($mysqli, $fetchPost);
        $row =  mysqli_fetch_assoc($postResult);

        $userOfPost = $row['userID'];
        
        //fetch user profile for post recieving the comments
        $fetchProfile = "SELECT username, profilePicture FROM user WHERE userID = '$userOfPost'";
        $profileResult = mysqli_query($mysqli, $fetchProfile);
        $profile = mysqli_fetch_assoc($profileResult);

        $post = $row['blogPostText'];

        //fetch comments on post
        $fetchComments = "SELECT * FROM commentblogpost WHERE blogPostID = '$currentPost'";
        $fetchCommentResult = mysqli_query($mysqli, $fetchComments);
        $comments = mysqli_fetch_assoc($fetchCommentResult);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comment | <?php echo($post);?></title>
    <link rel="stylesheet" type="text/css" href="css\mobile.css">
    <link rel="stylesheet" type="text/css" href="css\desktop.css" media="only screen and (min-width: 800px)">
</head>
<body class="container">
    <?php
        require_once('includes/HomeShortcut.php');
    ?>
    <div class="AllPostsContainer">
        <?php
            echo("<div class='post'>");
            echo("<div class = 'userPhoto'>");
            echo("<img src='{$profile['profilePicture']}' alt= 'Profile Picture'>");
            echo($profile["username"]);
            echo("</div>");
            if($row['blogPostText'] != "") {   //  add this when other bit is done && $row['blogPostImage'] != "" && $row['blogPostLink'] != "" && $row['blogPostVideo'] != ""
                echo("<div class = 'postContent'>");
                echo($row["blogPostText"] ?? null);
                echo($row['blogPostImage'] ?? null);
                echo($row['blogPostLink'] ?? null);
                echo($row['blogPostVideo'] ?? null);
                echo("</div>");
            }
            echo("</div>");        
    ?>
<form class="createPostContainer" method="POST">
            <div style="display: flex; flex-direction: row;">
                <textarea id="w3review" name="textContent" rows="4" cols="50"></textarea> <!-- textarea found on w3schools -->
                <button class = "createButton" type="submit" name="makeCommentButton">Create Comment</button>
            </div>
        </form>
    <?php            
        if (isset($_POST['makeCommentButton']) && $_POST['textContent'] != "") {
            $CommentContent = $_POST['textContent'] ?? null;
            $addToComments = $mysqli->prepare("INSERT INTO commentblogpost (blogPostID, userID, commentText, TimeOfComment) VALUES ('$currentPost', '$commenterUserID', '$CommentContent', now())");
            $addToComments->execute();header("Refresh:0; url=comments.php?post=$currentPost");
            
        }

        //comment section
        while($comments = mysqli_fetch_assoc($fetchCommentResult)) {
            //fetch commenter profile
            $commentUserID = $comments['userID'];
            $commenterProfile = "SELECT username, profilePicture FROM user WHERE userID = '$commentUserID'";
            $commenterProfileFetch = mysqli_query($mysqli, $commenterProfile);
            $commentProfile = mysqli_fetch_assoc($commenterProfileFetch);
            
            //fetch comment
            $Commentusername = $commentProfile['username'];
            $CommentText = $comments['commentText'];
            
            //display comment
            echo("<div class='post'>");
            echo("<div style='CommentUsername'>");
            echo("$Commentusername");
            echo("</div>");

            echo("$CommentText");
            echo("<span class='material-icons-outlined'> favorite_border</span>");
            echo("</div>");
        }
        //i think sometimes the comments dont go through
        ?>
    </div>
    <script src="js/main.js"></script>
</body>
</html>