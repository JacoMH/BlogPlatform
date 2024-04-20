<?php
    $postQuery = mysqli_query($mysqli, "SELECT * FROM blogpost WHERE userID = '$SessionUser'");

     //updates total likes in user table
     $totalLikesQuery = $mysqli->prepare("UPDATE user SET profileLikes = (SELECT SUM(likesOnPost) As 'totalLikes' FROM blogpost WHERE userID = '$SessionUser') WHERE userID = '$SessionUser'");
     $totalLikesQuery->execute();

        //filter
        $currentFilter = "";
        if (isset($_POST['Filters'])) {
            if ($_POST['Filters'] == "Most Recent") {
                $postQuery = mysqli_query($mysqli, "SELECT * FROM blogpost WHERE userID = '$SessionUser' ORDER BY DateAndTime DESC"); 
                $currentFilter = "Most Recent";
            }
            else if($_POST['Filters'] == "Most Commented") {
                //put on number of comments next to comment link, include comment number with post
                $postQuery = mysqli_query($mysqli, "SELECT * FROM blogpost WHERE userID = '$SessionUser' ORDER BY ");
                $currentFilter = "Most Commented";
            }
            else if($_POST['Filters'] == "Oldest") {
                $postQuery = mysqli_query($mysqli, "SELECT * FROM blogpost WHERE userID = '$SessionUser' ORDER BY DateAndTime ASC");
                $currentFilter = "Oldest";
            }
        }
        else{
            $postQuery = mysqli_query($mysqli, "SELECT * FROM blogpost WHERE userID = '$SessionUser' ORDER BY DateAndTime DESC"); 
            $currentFilter = "Most Recent";
        }

?>

<!DOCTYPE html>
<html lang="en">
<body>
    <div class="AllPostsContainer">
    
    <form method="POST" action="Profile.php">
    <label>Sort By:</label>
    <select name="Filters">
        <option selected="" disabled="" style='display: none;'><?php echo($currentFilter); ?></option>
        <option value="Most Recent">Most Recent</option>
        <option value="Most Commented">Most Commented</option>
        <option value="Oldest">Oldest</option>
    </select>
    <button type="submit" name="filterConfirm">Go</button>
    </form>
    <?php

        While ($post = mysqli_fetch_assoc($postQuery)) {
            echo("<div class = 'userPhoto'>");
            echo("<img src='{$_SESSION['profilePicture']}' alt= 'Profile Picture'>");
            echo("</div>");
            if($post['blogPostText'] != "") { //add these param afterwards && $row['blogPostImage'] != "" && $row['blogPostLink'] != "" && $row['blogPostVideo'] != ""
                echo("<div class = 'postContent'>");
                echo($post["blogPostText"]);
                echo($post['blogPostImage']);
                echo($post['blogPostLink']);
                echo($post['blogPostVideo']);
                echo("</div>");
                $postID = $post['blogPostID'];
                $blogPostText = $post['blogPostText'];
                
                //create like/dislike post
                $PostLikes = $post['likesOnPost'];
                echo("<form class='likeButton' method='POST' action='Profile.php'>");
                echo("<button name = '$postID'>like</button>");
                echo("</form>");
                //gather total likes and adds it to post to display and store in the database
                $likesQuery = mysqli_query($mysqli, "SELECT count(blogpostID) As 'likeCount' FROM userlikedposts WHERE blogpostID = '$postID'");
                $LikeNum = mysqli_fetch_assoc($likesQuery);
                echo($LikeNum['likeCount']);

                //updates likes in post table
                $storeLikesQuery = $mysqli->prepare("UPDATE blogpost SET likesOnPost = '{$LikeNum['likeCount']}' WHERE blogpostID = '$postID'");
                $storeLikesQuery->execute();

                //updates total likes in user table
                $totalLikesQuery = $mysqli->prepare("UPDATE user SET profileLikes = (SELECT SUM(likesOnPost) As 'totalLikes' FROM blogpost WHERE userID = '$SessionUser') WHERE userID = '$SessionUser'");
                $totalLikesQuery->execute();
                
                //update display
                $displayTotalLikes = mysqli_query($mysqli, "SELECT profileLikes FROM user WHERE userID = '$SessionUser'");
                While ($displayLikes = mysqli_fetch_assoc($displayTotalLikes)) {
                $_SESSION['profileLikes'] = $displayLikes['profileLikes']; 
                echo($_SESSION['profileLikes']);
                }

                //find number of comments
                $numOfCommentsQuery = mysqli_query($mysqli, "SELECT COUNT(blogPostID) AS NumOfComments FROM commentblogpost WHERE blogPostID = '$postID'");

                While ($numOfComments = mysqli_fetch_assoc($numOfCommentsQuery)) {
                    $commentNum = $numOfComments['NumOfComments'];
                    //toggle comments
                    if ($post['commentsEnabled'] == "on") {
                        echo("<span><a href='comments.php?post=$postID'>Comments($commentNum)</a></span>");
                    }
                    else if ($post['commentsEnabled'] == "") {
                        echo("<div class='smallCommentText'>");
                        echo("Comments Disabled");
                        echo("</div>");
                    }
                }
                echo("<div class='smallCommentText'>");
                echo($post['DateAndTime']);
                echo("</div>");

                //like/dislike post
                if (isset($_POST[$postID])) {
                    $checkIfLikedQuery = mysqli_query($mysqli, "SELECT * FROM userlikedposts WHERE userID = '$SessionUser' AND blogPostID = '$postID'");
                    $CheckLikedNumRows = mysqli_num_rows($checkIfLikedQuery); //found a method of liking and unliking a post without inserting duplicate keys into the database here: https://stackoverflow.com/questions/2848904/check-if-record-exists By User Dominic Rodger

                    if ($CheckLikedNumRows > 0) {
                        $removeLike = $mysqli->prepare("DELETE FROM userlikedposts WHERE userID = '$SessionUser' AND blogPostID = '$postID'");
                        $removeLike->execute();
                        
                    }
                    else {
                        $addLike = $mysqli->prepare("INSERT INTO userlikedposts (userID, blogPostID) VALUES($SessionUser, $postID)");
                        $addLike->execute();

                    }
                    header("refresh:0; url='Profile.php'");
                }
            }
        }
    ?>
    </div>
</body>
</html>