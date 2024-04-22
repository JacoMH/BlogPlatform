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

    //fetch comments
    if ($_GET['post']) {
        $fetchCommentsQuery = mysqli_query($mysqli, "SELECT * FROM commentblogpost WHERE blogPostID = '$currentPost'");
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
<body>
    <main class='container'>
    <?php
    if (!empty($_SESSION['username'])) {
            require_once('includes/profileShortcut.php');
            $SessionUser = $_SESSION['userID'];
        }
        else {
            require_once('includes/loginReg.php');
        }


        While($postToCommentOn = mysqli_fetch_assoc($fetchPostQuery)) {
            $userWhoMadePostID = $postToCommentOn['userID'];
            
            
            //fetch user receiving the comments
            $commentedUserQuery = mysqli_query($mysqli, "SELECT * FROM user WHERE userID = '$userWhoMadePostID' ");
            echo("<div>");
            While ($commentedUser = mysqli_fetch_assoc($commentedUserQuery)) {
                echo("<div style='display: flex; flex-direction: column;'>");
                echo("<div class = 'userPhoto'>");
                echo("<img class='userPhoto' src='{$commentedUser['profilePicture']}' alt= 'Profile Picture'>");
                echo("</div>");
                echo("</div>");
                echo("<span class='username' style='font-size: large; display: flex; justify-content: center;'> {$commentedUser['username']}</span>");
            }

            echo("<div class = 'postContent' style='display: flex; justify-content: center; background: green;'>");
                echo("<textarea style= 'background: green; border: none;'readonly>{$postToCommentOn['blogPostText']}</textarea>");
                echo($postToCommentOn['blogPostImage']);
                echo($postToCommentOn['blogPostLink']);
                echo($postToCommentOn['blogPostVideo']);
                echo("</div>");
            echo("</div>");
        }
    ?>
    <form class="createPostContainer" method="POST">
            <div style="display: flex; flex-direction: row; justify-content: center; padding: 10px;">
                <textarea id="w3review" name="textContent" rows="4" cols="50"></textarea> <!-- textarea found on w3schools -->
                <button class = "createButton" type="submit" name="makeCommentButton">Create Comment</button>
            </div>
        </form>
        
    <?php
        echo("<div class='AllPostsContainer'>");
                //make comment
                if (isset($_POST['makeCommentButton']) && $_POST['textContent'] != "") {
                    $CommentContent = $_POST['textContent'];
                    $addToComments = $mysqli->prepare("INSERT INTO commentblogpost (blogPostID, userID, commentText, TimeOfComment) VALUES ('$currentPost', '$commenterUserID', '$CommentContent', now())");
                    $addToComments->execute();
                    header("Refresh:0; url=comment.php?post=$currentPost");
                }

                $fetchCommentsQuery = mysqli_query($mysqli, "SELECT * FROM commentblogpost WHERE blogPostID = '$currentPost'");

                //show comments
                while ($fetchComments = mysqli_fetch_assoc($fetchCommentsQuery)) {
                   echo("<div>");

                   //get commenter profile
                   $commenterProfileQuery = mysqli_query($mysqli, "SELECT * FROM user WHERE userID = '{$fetchComments['userID']}'");
                   while ($fetchCommenterProfile = mysqli_fetch_assoc($commenterProfileQuery)) {
                    echo("<a href='user.php?user={$fetchCommenterProfile['userID']}'><img class='userPhoto' src = '{$fetchCommenterProfile['profilePicture']}' alt = 'Profile Picture'></a>");
                     echo($fetchCommenterProfile['username']);
                   }
                   echo("<div>");
                   echo($fetchComments['commentText']);
                   echo("</div>");
                    echo($fetchComments['LikesOnComment']);

                    
                   //create like button
                    echo("<form class='likeButton' method='POST' action='comment.php?post=$currentPost'>");
                    echo("<button name = '{$fetchComments['blogPostID']}'>like</button>");
                    echo("</form>");
                    
                    //like/dislike post
                    echo($fetchComments['LikesOnComment']);

                    if (isset($_POST["{$fetchComments['blogPostID']}"])) {
                        if (isset($_POST["{$fetchComments['blogPostID']}"])) {
                            $checkIfLikedQuery = mysqli_query($mysqli, "SELECT * FROM userlikedcomments WHERE userID = '$SessionUser' AND CommentID = '{$fetchComments['commentID']}'"); //check if user has liked post or not
                            $CheckLikedNumRows = mysqli_num_rows($checkIfLikedQuery); //found a method of liking and unliking a post without inserting duplicate keys into the database here: https://stackoverflow.com/questions/2848904/check-if-record-exists By User Dominic Rodger
    
                            if ($CheckLikedNumRows > 0) {
                                echo("hello");
                                $removeLike = $mysqli->prepare("DELETE FROM userlikedcomments WHERE userID = '$SessionUser' AND commentID = '{$fetchComments['commentID']}'");
                                $removeLike->execute();
                                
                            }
                            else {
                                echo("goodbye");
                                $addLike = $mysqli->prepare("INSERT INTO userlikedcomments (userID, CommentID) VALUES($SessionUser, {$fetchComments['commentID']})");
                                $addLike->execute();
    
                            }
                            header("refresh:0; url='comment.php?post=$currentPost'");
                        }
                    }
                echo("</div>");
                echo("</div>");
                }
                include("Includes/footer.php");
    ?>
    </main>
</body>
</html>