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
            echo("<div style='align-self: center; max-width: 200px; display: flex; flex-direction: row; justify-content: center; '>");
            While ($commentedUser = mysqli_fetch_assoc($commentedUserQuery)) {
                echo("<div style='display: flex; flex-direction: column; padding-right: 5px; align-self: center; '>");
                echo("<div style='display: flex; justify-content: column;'>");
                echo("<div class = 'userPhoto'>");
                echo("<img class='userPhoto' src='{$commentedUser['profilePicture']}' alt= 'Profile Picture'>");
                echo("</div>");
                echo("</div>");
                echo("<span class='username' style='font-size: large; display: flex; justify-content: center;'> {$commentedUser['username']}</span>");
                echo("</div>");
            }
            echo("<div>");
            echo("<div class = 'postContent' style='display: flex; justify-content: center; background: green; min-width: 400px; max-width: 1000px; align-items: center;'>");
                echo("<textarea style= 'background: green; border: none;'readonly>{$postToCommentOn['blogPostText']}</textarea>");
                echo("<img class= 'tempPostImage' src='{$postToCommentOn['blogPostImage']}'>");
                echo("<a href={$postToCommentOn['blogPostLink']}>{$postToCommentOn['blogPostVideo']}</a>");
                //video
                if (!empty($postToCommentOn['blogPostVideo'])) {
                    $parse = parse_url($postToCommentOn['blogPostVideo']); 
                    $query = $parse['query'];
                    $final=substr($query,2);
                    echo("<iframe width='220' height='215' src='https://www.youtube.com/embed/{$final}'></iframe>"); 
                }
                echo("</div>");
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
                $checkIfComments = mysqli_num_rows($fetchCommentsQuery);
                
                if ($checkIfComments > 0) {
                        //show comments
                        echo("<div style='display: flex; flex-direction: column;'");
                while ($fetchComments = mysqli_fetch_assoc($fetchCommentsQuery)) {
                    echo("<div class='post' style='display: flex; flex-direction: row; align-items: center;'>");
                 
 
                    //get commenter profile
                    $commenterProfileQuery = mysqli_query($mysqli, "SELECT * FROM user WHERE userID = '{$fetchComments['userID']}'");
                    while ($fetchCommenterProfile = mysqli_fetch_assoc($commenterProfileQuery)) {
                     echo("<div class='BloggerProfile'>");
                     echo("<a href='user.php?user={$fetchCommenterProfile['userID']}'><img class='userPhoto' src = '{$fetchCommenterProfile['profilePicture']}' alt = 'Profile Picture'></a>");
                      echo("<span style='font-size: large; display: flex; justify-content: center;'> {$fetchCommenterProfile['username']}</span>");
                      echo("</div>");
                    }
                    echo("<div style='padding: 10px; background: green; padding: 10px; border-radius: 8px;'>");
                    echo("<textarea style= 'background: green; border: none;'readonly>{$fetchComments['commentText']}</textarea>");
                    echo("</div>");
 
                    //update total likes on comment (in comment likes table)
                    $commentID = $fetchComments['commentID'];
                    $updateCommentLikesQuery = mysqli_query($mysqli, "SELECT count(commentID) As count FROM userlikedcomments WHERE commentID = '$commentID'");
 
                    //update in comment table
                    while ($updateLikes = mysqli_fetch_assoc($updateCommentLikesQuery)) {
                     $LikeCommentQuery = mysqli_query($mysqli, "UPDATE commentblogpost SET LikesOnComment = '{$updateLikes['count']}' WHERE commentID = '$commentID'");
                    }   
 
 
 
                    //if admin or moderator, can delete comment
                    if ($_SESSION['jobTitle'] == 'Admin' || $_SESSION['jobTitle'] == 'Moderator') {
                     echo("<form class='deleteButton' method='POST' action='comment.php?post=$currentPost'>");
                     echo("<button type = 'submit' name = 'delete$commentID'>delete</button>");
                     echo("</form>");
                    }
 
                    if (isset($_POST["delete$commentID"])) {
                     $deleteCommentQuery = mysqli_query($mysqli, "DELETE FROM commentblogpost WHERE commentID = '$commentID'");
                     echo "<script> window.location.href='comment.php?post=$currentPost''</script>";
                    }
 
                    //create like button
                     echo("<form class='likeButton' method='POST' action='comment.php?post=$currentPost'>");
                     echo("<button name = '{$fetchComments['blogPostID']}'>like</button>");
                     echo("</form>");
                     
                     //like/dislike post
                     $CommentLikesQuery = mysqli_query($mysqli, "SELECT count(commentID) As count FROM userlikedcomments WHERE commentID = '$commentID'");
                     while($ShowLikes = mysqli_fetch_assoc($CommentLikesQuery)) {
                         echo($ShowLikes['count']);
                        }
 
                     if (isset($_POST["{$fetchComments['blogPostID']}"])) {
                         if (isset($_POST["{$fetchComments['blogPostID']}"])) {
                             $checkIfLikedQuery = mysqli_query($mysqli, "SELECT * FROM userlikedcomments WHERE userID = '$SessionUser' AND CommentID = '{$fetchComments['commentID']}'"); //check if user has liked post or not
                             $CheckLikedNumRows = mysqli_num_rows($checkIfLikedQuery); //found a method of liking and unliking a post without inserting duplicate keys into the database here: https://stackoverflow.com/questions/2848904/check-if-record-exists By User Dominic Rodger
     
                             if ($CheckLikedNumRows > 0) {
                                 $removeLike = $mysqli->prepare("DELETE FROM userlikedcomments WHERE userID = '$SessionUser' AND commentID = '{$fetchComments['commentID']}'");
                                 $removeLike->execute();
                                 
                             }
                             else {
                                 $addLike = $mysqli->prepare("INSERT INTO userlikedcomments (userID, CommentID) VALUES($SessionUser, {$fetchComments['commentID']})");
                                 $addLike->execute();
     
                             }
                             echo "<script> window.location.href='comment.php?post=$currentPost''</script>";
                         }
                     }
                }
                }
                else {
                    echo("no comment");
                }
                echo("</div>");
                
                echo("</div>");
                echo("</div>");
                
    ?>
    </main>
    <div class='container'>
    <?php
        include("Includes/footer.php");
    ?>
    </div>
</body>
</html>